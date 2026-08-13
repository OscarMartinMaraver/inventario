<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder;

class PurchaseTable extends DataTableComponent
{
    /**
     * * Reemplazamos la propiedad "$model = PurchaseOrder::class" por el método builder()
     * para realizar "Eager Loading" (carga ambiciosa) de la relación 'supplier'.
     *
     * Esto evita el problema de N+1 consultas: sin el "with('supplier')", Laravel realizaría 
     * 1 consulta para traer las N órdenes de compra y luego N consultas individuales más 
     * para obtener el proveedor de cada una. Con este método, todo se resuelve en solo 2 consultas.
     */

    //protected $model = PurchaseOrder::class;

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
            Column::make("Date", "date")
                ->sortable()
                ->format(fn($value) => $value->format('d-m-Y')),
            Column::make("Serie", "serie"),
            Column::make("Correlativo", "correlative"),
            Column::make("Documento", "supplier.document_number")
                ->sortable()
                ->searchable(),
            Column::make("Razon social", "supplier.name")
                ->sortable()
                ->searchable(),
            Column::make("Total", "total")
                ->sortable()
                ->format(fn($value) => '€/ ' . number_format($value, 2, '.', ',')),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.purchase_orders.actions', ['purchasOrder' => $row]);
                    //Con format() capturamos un valor de una celda y lo formateamos
                    //Con label() capturamos la fila completa para manipularla. Solo llama los valores que hemos definido en las columnas, si hay algo que no se muestra, 
                    //habría que añadirlo como algo adicional en la funcion configure()
                })
        ];
    }

    //para evitar el problema N+1, cargo las relaciones con la función builder
    public function builder(): Builder
    {
        return Purchase::query()->with('supplier');
    }
}
