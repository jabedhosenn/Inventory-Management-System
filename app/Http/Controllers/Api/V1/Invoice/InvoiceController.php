<?php

namespace App\Http\Controllers\Api\V1\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\StockMovement;
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

    public function createStockMovement(Invoice $invoice)
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
}
