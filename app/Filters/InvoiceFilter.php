<?php

namespace App\Filters;

use DeepCopy\Exception\PropertyException;
use Illuminate\Http\Request;

class InvoiceFilter extends Filter
{
    protected  array $allowedOperatorsFields = [
        'value' => ['gt', 'lt', 'eq', 'ne', 'gte', 'lte'],
        'type' => ['eq', 'ne', 'in'],
        'paid' => ['eq', 'ne'],
        'payment_date' => ['gt', 'lt', 'eq', 'ne', 'gte', 'lte'],
    ];


}
