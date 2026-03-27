<?php

namespace App\Http\Controllers\Auth;

use App\Cruds\Squema\Basics\BasicsCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Juaniquillo\InputComponentAction\Containers\InputComponentOutput;
use Juaniquillo\InputComponentAction\Groups\NoWrapSoleInputGroup;
use Juaniquillo\InputComponentAction\InputComponentAction;

class BasicsController extends Controller
{
    public function __invoke(Request $request)
    {
        $crud = BasicsCrud::make();

        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $output = $crud->execute(
            (new InputComponentAction($values, $errors))
                ->setDefaultInputGroup(NoWrapSoleInputGroup::class)
                ->setDefaultComponentBag(BasicsCrud::dashboardComponentBag())
        );

        /** @var InputComponentOutput $output */
        $inputs = $output->inputs;

        $form = BasicsCrud::form($inputs->toArray());

        return view('dashboard.basics')
            ->with('form', $form);
    }
}
