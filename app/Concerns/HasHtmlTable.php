<?php

namespace App\Concerns;

use App\Cruds\Actions\Presenters\TableComponentUtil;
use App\Cruds\Actions\Presenters\TableRowsAction;
use Illuminate\Support\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

trait HasHtmlTable
{
    public function makeTable(Collection $collection)
    {
        if ($collection->isEmpty()) {
            return null;
        }

        $crud = $this->make();
        $cells = [];

        foreach ($collection as $model) {
            $output = $crud->execute(
                new TableRowsAction(
                    model: $model,
                )
            );

            $cells[] = $output->toArray();

        }

        $rows = $this->makeTableRows($cells);

        $headersLabels = array_keys($cells[0] ?? []);
        $headers = $this->makeTableHeaders($headersLabels);

    }

    public function makeTableRows(array $cells): BackendComponent|CompoundComponent
    {
        return TableComponentUtil::rows(
            cells: $cells
        );
    }

    public function makeTableHeaders(array $headersLabels): array
    {
        $headers = [];
        foreach ($headersLabels as $label) {
            $headers[] = TableComponentUtil::headers(
                headers: $label
            );
        }

        return $headers;

    }
}
