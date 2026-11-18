<?php

namespace App\Cruds\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\Utils\TableComponentUtil;

trait HasHtmlTable
{
    public function makeTable(Collection|LengthAwarePaginator $collection): BackendComponent|CompoundComponent|null
    {
        if ($collection->isEmpty()) {
            return null;
        }

        $crud = $this->make();
        $rows = [];
        $outputArray = [];

        $util = new TableComponentUtil(
            component: FluxBackendComponent::class
        );

        foreach ($collection as $model) {

            $action = new TableCellsAction(
                model: $model,
                component: FluxBackendComponent::class,
                type: FluxComponentEnum::TD,
            );

            /** Row actions */
            $this->tableOptions($action);

            $output = $crud->execute($action);

            $outputArray = $output->toArray();

            $rows[] = $this->tableRows($util, $outputArray, $model);

        }

        /** Generate headers based on the last model's output array keys */
        $headers = $this->tableHeaders($util, $outputArray);

        return $this->tableComponent($util, $headers, $rows);

    }

    private function tableRows(TableComponentUtil $util, array $outputArray, Model $model): BackendComponent|CompoundComponent
    {
        return $util->row(
            cells: $outputArray,
            type: FluxComponentEnum::TR,
            attributes: [
                'wire:key' => 'table-row-'.$model->getKey(),
            ],
        );
    }

    private function tableComponent(TableComponentUtil $util, array $headers, array $rows): BackendComponent|CompoundComponent
    {
        $tableContents = [
            $util->head(
                headers: $headers,
                type: FluxComponentEnum::THEAD,
                rowType: FluxComponentEnum::TR,
            ),
            $util->body(
                rows: $rows,
                type: FluxComponentEnum::TBODY
            ),
        ];

        return $util->table(
            contents: $tableContents,
            type: FluxComponentEnum::TABLE
        );
    }

    private function tableHeaders(TableComponentUtil $util, $outputArray): array
    {
        return array_map(function ($key) use ($util) {
            return $util->header(
                header: $key,
                type: FluxComponentEnum::TH
            );
        },
            array_keys($outputArray));
    }

    protected function tableOptions(TableCellsAction $action): void {}
}
