<?php

namespace App\Models;

use App\Filters\InvoiceFilter;
use App\Http\Resources\v1\InvoiceResouce;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'paid',
        'payment_date',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filter(Request $request)
    {
        $queryFilter = (new InvoiceFilter())->filter($request);
        if (empty($queryFilter)){
            return InvoiceResouce::collection(Invoice::with('user')->get());
        }
        $data = Invoice::with('user');
        if (!empty($queryFilter['whereIn'])){
            foreach ($queryFilter['whereIn'] as $whereIn){
                $data->whereIn($whereIn[0], $whereIn[1]);
            }
        }
        $resource = $data->where($queryFilter['where'])->get();
        return InvoiceResouce::collection($resource);
    }
}
