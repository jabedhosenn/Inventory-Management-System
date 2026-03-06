<?php

namespace App\Http\Controllers\Api\V1\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $invoices = Invoice::with(['items.product.category'])
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Invoices list fetched successfully',
                'data' => $invoices,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch invoices',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'invoice_no' => ['nullable', 'string', 'max:255', 'unique:invoices,invoice_no'],
                'invoice_date' => ['required', 'date'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                'items.*.unit_price' => ['required', 'numeric', 'min:0'],
                'items.*.discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'items.*.discount_value' => ['required', 'numeric', 'min:0'],
                'items.*.discount_amount' => ['required', 'numeric', 'min:0'],
                'items.*.line_total' => ['required', 'numeric', 'min:0'],
                'subtotal' => ['required', 'numeric', 'min:0'],
                'discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'discount_value' => ['required', 'numeric', 'min:0'],
                'discount_amount' => ['required', 'numeric', 'min:0'],
                'grand_total' => ['required', 'numeric', 'min:0'],
                'status' => ['nullable', 'string', 'in:draft,finalized,cancelled'],
            ]);

            // Start transaction
            DB::beginTransaction();

            if (empty($validated['invoice_no'])) {
                $validated['invoice_no'] = $this->generateInvoiceNumber();
            }

            $invoice = Invoice::create([
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $validated['subtotal'],
                'discount_type' => $validated['discount_type'] ?? null,
                'discount_value' => $validated['discount_value'],
                'discount_amount' => $validated['discount_amount'],
                'grand_total' => $validated['grand_total'],
                'status' => $validated['status'] ?? 'draft',
            ]);

            // Create invoice items
            foreach ($validated['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_type' => $item['discount_type'] ?? null,
                    'discount_value' => $item['discount_value'],
                    'discount_amount' => $item['discount_amount'],
                    'line_total' => $item['line_total'],
                ]);
            }

            // Create stock movement for the invoice
            if ($invoice->status === 'finalized') {
                $this->createStockMovement($invoice);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => $invoice->load(['items.product.category']),
            ], 201);
        } catch (ValidationException $ve) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors(),
            ], 422);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::with(['items.product.category'])->find($id);
            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Invoice details fetched successfully',
                'data' => $invoice,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch invoice details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $invoice = Invoice::with(['items'])->find($id);
            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found',
                ], 404);
            }

            if ($invoice->status === 'finalized') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update a finalized invoice',
                ], 400);
            }

            $validated = $request->validate([
                'invoice_no' => ['sometimes', 'string', 'max:255', 'unique:invoices,invoice_no,' . $invoice->id],
                'invoice_date' => ['sometimes', 'required', 'date'],
                'items' => ['sometimes', 'required', 'array', 'min:1'],
                'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                'items.*.unit_price' => ['required', 'numeric', 'min:0'],
                'items.*.discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'items.*.discount_value' => ['required', 'numeric', 'min:0'],
                'items.*.discount_amount' => ['required', 'numeric', 'min:0'],
                'items.*.line_total' => ['required', 'numeric', 'min:0'],
                'subtotal' => ['sometimes', 'required', 'numeric', 'min:0'],
                'discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'discount_value' => ['sometimes', 'numeric', 'min:0'],
                'discount_amount' => ['sometimes', 'required', 'numeric', 'min:0'],
                'grand_total' => ['sometimes', 'required', 'numeric', 'min:0'],
                'status' => ['sometimes', 'string', 'in:draft,finalized,cancelled'],
                // Status update is not allowed here to prevent stock movement issues
            ]);

            // Start transaction
            DB::beginTransaction();

            $oldStatus = $invoice->status;

            if (isset($validated['items'])) {
                // Delete old items and create new ones
                $invoice->items()->delete();
                // Create new invoice items
                foreach ($validated['items'] as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount_type' => $item['discount_type'] ?? null,
                        'discount_value' => $item['discount_value'],
                        'discount_amount' => $item['discount_amount'],
                        'line_total' => $item['line_total'],
                    ]);
                }
            }

            $updateData = [
                'invoice_no' => $validated['invoice_no'] ?? $invoice->invoice_no,
                'invoice_date' => $validated['invoice_date'] ?? $invoice->invoice_date,
                'discount_type' => $validated['discount_type'] ?? $invoice->discount_type,
                'discount_value' => $validated['discount_value'] ?? $invoice->discount_value,
                'status' => $validated['status'] ?? $invoice->status,
            ];

            if (isset($validated['subtotal'])) {
                $updateData['subtotal'] = $validated['subtotal'];
                // Recalculate discount amount and grand total based on the new subtotal
                $updateData['discount_amount'] = $this->calculateDiscountAmount($updateData['subtotal'], $updateData['discount_type'], $updateData['discount_value']);
                $updateData['grand_total'] = $updateData['subtotal'] - $updateData['discount_amount'];
            } else if (isset($validated['discount_amount'])) {
                $updateData['discount_amount'] = $validated['discount_amount'];
                // Recalculate grand total based on the new discount amount
                $updateData['grand_total'] = ($invoice->subtotal ?? 0) - $updateData['discount_amount'];
            } else if (isset($validated['grand_total'])) {
                $updateData['grand_total'] = $validated['grand_total'];
            }

            $invoice->update($updateData);
            // If status changed to finalized, create stock movement
            $newStatus = $updateData['status'] ?? $invoice->status;
            if ($oldStatus !== 'finalized' && $newStatus === 'finalized') {
                $this->createStockMovement($invoice->fresh());
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'data' => $invoice->load(['items.product.category']),
            ]);
        } catch (ValidationException $ve) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $invoice = Invoice::find($id);
            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found',
                ], 404);
            }

            if ($invoice->status === 'finalized') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete a finalized invoice',
                ], 400);
            }

            DB::beginTransaction();
            $invoice->items()->delete();
            $invoice->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function createStockMovement(Invoice $invoice)
    {
        // Logic to create stock movement based on the invoice items
        foreach ($invoice->items as $item) {
            // Create stock movement for each item
            $product = Product::findOrFail($item->product_id);
            // Check if stock is sufficient before creating movement
            if ($product->stock_qty < $item->quantity) {
                throw new \Exception("Insufficient stock for product: {$product->product_name}. Available: {$product->stock_qty}, Required: {$item->quantity}");
            }

            // Create stock movement (this is a placeholder, implement as needed)
            StockMovement::create([
                'product_id' => $item->product_id,
                'quantity' => -$item->quantity, // Reduce stock
                'type' => 'OUT', // Stock out for invoice
                'note' => "Stock OUT for invoice #{$invoice->invoice_no}",
                'invoice_id' => $invoice->id,
            ]);

            // Update product stock
            $product->stock_qty -= $item->quantity;
            $product->save();
        }
    }

    private function calculateDiscountAmount($subtotal, $discountType, $discountValue)
    {
        if ($discountType === 'fixed') {
            return min($discountValue, $subtotal); // Discount cannot exceed subtotal
        } elseif ($discountType === 'percent') {
            return ($subtotal * $discountValue) / 100;
        }
        return 0;
    }

    private function generateInvoiceNumber()
    {
        // $latestInvoice = Invoice::orderByDesc('id')->first();
        // if ($latestInvoice) {
        //     $lastNumber = (int) str_replace('INV-', '', $latestInvoice->invoice_no);
        //     return 'INV-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        // }
        // return 'INV-000001';

        // INV-2026-01-0001
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');

        // Get the latest invoice for the current month and year
        $lastInvoice = Invoice::where('invoice_no', 'like', "INV-{$year}-{$month}-%")
            ->orderByDesc('invoice_no')
            ->first();

        if ($lastInvoice) {
            $sequence = (int) substr($lastInvoice->invoice_no, -4) + 1;
        } else {
            $sequence = 1;
        }
        return sprintf('INV-%s-%s-%04d', $year, $month, $sequence);
    }
}
