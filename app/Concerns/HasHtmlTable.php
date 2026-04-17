<?php

namespace App\Concerns;

use App\Components\ThirdParty\Flux\FluxBackendComponent;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableComponentUtil;
use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

trait HasHtmlTable
{
    public function makeTable(Collection $collection): BackendComponent|CompoundComponent|null
    {
        if ($collection->isEmpty()) {
            return null;
        }

        $crud = $this->make();
        $rows = [];
        $headers = [];

        $util = new TableComponentUtil(
            component: FluxBackendComponent::class
        );

        foreach ($collection as $key => $model) {

            $action = $this->tableAction($model);
            $output = $crud->execute($action);

            $outputArray = $output->toArray();

            if ($key === 0) {
                $headers = $this->tableHeaders($util, $outputArray);
            }

            $rows[] = $this->tableRows($util, $outputArray);

        }

        return $this->tableComponent($util, $headers, $rows);

    }

    public function tableAction(Model $model)
    {
        return new TableRowsAction(
            model: $model,
            component: FluxBackendComponent::class,
            type: FluxComponentEnum::TD,
        );
    }

    public function tableRows(TableComponentUtil $util, array $outputArray): BackendComponent|CompoundComponent
    {
        return $util->rows(
            cells: $outputArray,
            type: FluxComponentEnum::TR,
        );
    }

    public function tableComponent(TableComponentUtil $util, array $headers, array $rows): BackendComponent|CompoundComponent
    {
       $tableContents = [
            $util->tHead(
                headers: $headers,
                type: FluxComponentEnum::THEAD
            ),
            $util->tBody(
                rows: $rows,
                type: FluxComponentEnum::TBODY
            ),
        ];

        return $util->table(
            contents: $tableContents,
            type: FluxComponentEnum::TABLE
        );
    }

    public function tableHeaders(TableComponentUtil $util, $outputArray): array
    {
        return array_map(function ($key) use ($util) {
            return $util->header(
                header: $key,
                type: FluxComponentEnum::TH
            );
        },
        array_keys($outputArray));
    }

    public function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            
        );

        $action->setExtraCell('settings', $recipe);
    }

}
