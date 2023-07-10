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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InvoiceResouce::collection(Invoice::with('user')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new InvoiceResouce(Invoice::where('id', $id)->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
