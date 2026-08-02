<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Warehouse;

class WarehouseTable extends DataTableComponent
{
    protected $model = Warehouse::class;
    public string $tableName = 'warehouses_table';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),
            Column::make("Localización", "location")
                ->searchable()
                ->sortable(),
                Column::make("Acciones")
            ->label(function($row){
                return view('admin.warehouses.actions', ['warehouse' => $row]);
            })

        ];
    }
}
