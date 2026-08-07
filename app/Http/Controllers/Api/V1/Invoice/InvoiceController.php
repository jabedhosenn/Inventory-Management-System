<?php

namespace App\Http\Controllers\Api\V1\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

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
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching invoices',
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

            // Invoice creation logic goes here
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

            // Create invoice items
            foreach ($validated['items'] as $itemData) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount_type' => $itemData['discount_type'] ?? null,
                    'discount_value' => $itemData['discount_value'],
                    'discount_amount' => $itemData['discount_amount'],
                    'line_total' => $itemData['line_total'],
                ]);
            }

            // Create stock movements for the invoice items if finalized
            if ($invoice->status === 'finalized') {
                $this->createStockMovement($invoice);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => $invoice->load(['items.product.category']),
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating invoice',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function createStockMovement(Invoice $invoice)
    {
        foreach ($invoice->items as $item) {
            // Logic to create stock movement for each item
            $product = Product::findOrFail($item->product_id);

            //Check stock availability
            if ($product->stock_qty < $item->quantity) {
                throw new \Exception("Insufficient stock for product : {$product->product_name}. Available stock: {$product->stock_qty}, Required: {$item->quantity}");
            }

            //create stock movement
            StockMovement::create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'type' => 'OUT',
                'note' => "Stock OUT for Invoice #{$invoice->invoice_no}",
                'invoice_id' => $invoice->id,
            ]);

            //Update product stock quantity
            $product->stock_qty -= $item->quantity;
            $product->save();
        }
    }
}
