<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Category;

class CategoryTable extends DataTableComponent
{
    protected $model = Category::class;
    public string $tableName = 'categories_table';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');//Nos permite ordenar la tabla por defecto
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),//Para permitir la busqueda
            Column::make("Descripción", "description")
                ->sortable(),

                //Para crear un botón de accdión uso Column::make, llamo al método label y paso una función anónima que reciba la información de la fila mediante el parámetro $row.
                //La función devolverá una vista a la que se le pasa el campo category correspondiente a la fila.
            Column::make("Acciones")
            ->label(function($row){
                return view('admin.categories.actions', ['category' => $row]);
            })
        ];
    }
}
