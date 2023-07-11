<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InvoiceResouce;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use HttpResponses;

//    public function __construct()
//    {
//        $this->middleware('auth:sanctum')->only(['store', 'update']);
//    }

    public function index(Request $request)
    {
//        return InvoiceResouce::collection(Invoice::with('user')->get());
//        return InvoiceResouce::collection(Invoice::where([
//            ['value', '>', 5000],
//            ['paid', '=', 1],
//        ])->with('user')->get());
    return (new Invoice())->filter($request);


    }

    public function store(Request $request)
    {
        if (!auth()->user()->tokenCan('invoice-store')){
            return $this->error('Unauthorized', 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1',
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable',
            'value' => 'required|numeric|between:0,999999.99',
        ]);


        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $created = Invoice::create($validator->validated());

        if ($created) {
            return $this->response('Invoice created successfully', 201,
                new InvoiceResouce($created->load('user')));
        }
        return $this->error('Error creating invoice', 400);
    }

    public function show(string $id)
    {

        return new InvoiceResouce(Invoice::where('id', $id)->first());
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1|in:' . implode(',', ['B','C','P']),
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }
        $valideted = $validator->validated();



        $updated = $invoice->update([
            'user_id' => $valideted['user_id'],
            'type' => $valideted['type'],
            'paid' => $valideted['paid'],
            'value' => $valideted['value'],
            'payment_date' => $valideted['paid'] ? $valideted['payment_date'] : null,
        ]);
        if ($updated) {
            return $this->response('Invoice updated successfully', 200,
                new InvoiceResouce($invoice->first()->load('user')));
        }
        return $this->error('Error updating invoice', 400);

    }
    public function destroy(Invoice $invoice)
    {
        $deleted = $invoice->delete();
        if ($deleted) {
            return $this->response('Invoice deleted successfully', 200);
        }
        return $this->error('Error deleting invoice', 400);
    }
}
