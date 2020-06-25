<?php


namespace LaravelVueGoodTable;


use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use LaravelVueGoodTable\Columns\Column;
use LaravelVueGoodTable\Http\Requests\ConfigRequest;
use LaravelVueGoodTable\Http\Requests\DataRequest;
use LaravelVueGoodTable\Http\Responses\ConfigResponse;
use LaravelVueGoodTable\Http\Responses\DataResponse;

trait InteractsWithVueGoodTable
{
    use PerformsFiltering;
    use PerformsSearching;
    use PerformsSorting;
    use PerformsPagination;

    /**
     * @param Request $request
     *
     * @return Builder
     */
    abstract protected function getQuery(Request $request);

    /**
     * @return Column[]
     */
    abstract protected function getColumns(): array;

    /**
     * @param ConfigRequest $request
     *
     * @return ConfigResponse
     */
    public function handleConfigRequest(ConfigRequest $request): ConfigResponse
    {
        $columns = $this->getColumns();

        $config = new ComponentConfig($columns);

        return new ConfigResponse($config);
    }

    /**
     * @param DataRequest $request
     *
     * @return DataResponse
     */
    public function handleDataRequest(DataRequest $request): DataResponse
    {
        $queryBuilder = $this->getQuery($request);
        $columns = $this->getColumns();

        $queryBuilder = $this->applyFiltering($queryBuilder, $request);
        $queryBuilder = $this->applySearching($columns, $queryBuilder, $request);
        $queryBuilder = $this->applySorting($columns, $queryBuilder, $request);

        $total = $queryBuilder->count();

        $queryBuilder = $this->applyPagination($queryBuilder, $request);

        $rows = $queryBuilder->get();

        $data = app(ColumnsValuesGetter::class)->handle($columns, $rows);

        return new DataResponse($data, $total);
    }
}