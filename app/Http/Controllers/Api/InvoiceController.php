<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        return InvoiceResource::collection(Invoice::with('vehicle')->paginate());
    }

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice->load('vehicle'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'transaction_number' => 'nullable|string',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|in:paid,unpaid',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create the invoice
        $invoice = Invoice::create([
            'vehicle_id' => $request->input('vehicle_id'),
            'transaction_number' => $request->input('transaction_number'),
            'amount' => $request->input('amount'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status'),
        ]);

        // Return the newly created invoice resource
        return new InvoiceResource($invoice->load('vehicle'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:paid,unpaid',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the status
        $invoice->status = $request->input('status');
        $invoice->save();

        // Return the updated invoice resource
        return new InvoiceResource($invoice);
    }
}
