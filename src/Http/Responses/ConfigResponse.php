<?php


namespace LaravelVueGoodTable\Http\Responses;


use Illuminate\Http\JsonResponse;
use LaravelVueGoodTable\Columns\Column;
use LaravelVueGoodTable\ComponentConfig;
use LaravelVueGoodTable\Contracts\Searchable;
use LaravelVueGoodTable\Contracts\Sortable;

class ConfigResponse extends JsonResponse
{
    /**
     * ConfigResponse constructor.
     *
     * @param ComponentConfig $config
     */
    public function __construct(ComponentConfig $config)
    {
        $data = [
            'columns' => $this->jsonSerialize($config->getColumns())
        ];

        parent::__construct($data);
    }

    /**
     * @param Column[] $columns
     *
     * @return array
     */
    protected function jsonSerialize(array $columns): array
    {
        $items = [];
        foreach ($columns as $column) {
            $items[] = $column->jsonSerialize();
        }

        return $items;
    }
}