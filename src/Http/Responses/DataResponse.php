<?php


namespace LaravelVueGoodTable\Http\Responses;

use Illuminate\Http\JsonResponse;

class DataResponse extends JsonResponse
{
    public function __construct(Iterable $rows, int $total)
    {
        $data = [
            'rows' => $rows,
            'totalRecords' => $total
        ];

        parent::__construct($data);
    }
}