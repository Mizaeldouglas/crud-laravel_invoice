<?php

namespace App\Filters;

use DeepCopy\Exception\PropertyException;
use Illuminate\Http\Request;
use Exception;

abstract class Filter
{
    protected array $allowedOperatorsFields = [];
    protected array $translateOperatorsFileds = [
        'gt' => '>',
        'lt' => '<',
        'eq' => '=',
        'ne' => '!=',
        'gte' => '>=',
        'lte' => '<=',
        'in' => 'in',
    ];

    public function filter(Request $request)
    {
        $where = [];
        $whereIn = [];

        if (empty($this->allowedOperatorsFields)) {
            throw new PropertyException('Allowed operators fields is empty');
        }

        foreach ($this->allowedOperatorsFields as $param => $operators) {
            $queryOperator = $request->query($param);
            if ($queryOperator) {
                foreach ($queryOperator as $operator => $value) {
                    if (!in_array($operator, $operators)) {
                        throw new Exception("Operator {$operator} not allowed for field {$param}");
                    }

                    if (str_contains($value, '[')) {
                        $whereIn[] = [
                            $param,
                            explode(',', str_replace(['[', ']'], ['', ''], $value)),
                            $value
                        ];
                    } else {
                        $where[] = [
                            $param,
                            $this->translateOperatorsFileds[$operator],
                            $value
                        ];
                    }
                }
            }
        }
        if (empty($where) && empty($whereIn)) {
            return [];
        }
        return [
            'where' => $where,
            'whereIn' => $whereIn
        ];
    }

}
