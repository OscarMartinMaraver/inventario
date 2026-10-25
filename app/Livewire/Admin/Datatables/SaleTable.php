<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class SaleTable extends DataTableComponent
{
    /**
     * * Reemplazamos la propiedad "$model = Sale::class" por el método builder()
     * para realizar "Eager Loading" (carga ambiciosa) de la relación 'customer'.
     *
     * Esto evita el problema de N+1 consultas: sin el "with('customer')", Laravel realizaría 
     * 1 consulta para traer las N ventas y luego N consultas individuales más 
     * para obtener el cliente de cada una. Con este método, todo se resuelve en solo 2 consultas.
     */

    //protected $model = Sale::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');

        $this->setConfigurableAreas([
            'after-wrapper' => [
                'admin.pdf.modal'
            ],
        ]);
    }

     public function filters(): array{
        return[
            DateRangeFilter::make('Fecha')
            ->config([
                'placeholder' => 'Desde - Hasta',
            ])
            ->filter(function(Builder $query, array $dateRange) {
                $query->whereBetween('date', [
                    $dateRange['minDate'], 
                    $dateRange['maxDate']
                    ]);
            }),
        ];
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
            Column::make("Documento", "customer.document_number")
                ->sortable()
                ->searchable(),
            Column::make("Razon social", "customer.name")
                ->sortable()
                ->searchable(),
            Column::make("Total", "total")
                ->sortable()
                ->format(fn($value) => '€/ ' . number_format($value, 2, '.', ',')),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.sales.actions', ['sale' => $row]);
                    //Con format() capturamos un valor de una celda y lo formateamos
                    //Con label() capturamos la fila completa para manipularla. Solo llama los valores que hemos definido en las columnas, si hay algo que no se muestra, 
                    //habría que añadirlo como algo adicional en la funcion configure()
                })
        ];
    }

    //para evitar el problema N+1, cargo las relaciones con la función builder
    public function builder(): Builder
    {
        return Sale::query()->with('customer');
    }

 
    //PROPIEDADES

    public $form = [
        'open' => false,
        'document' => '',
        'client' => '',
        'email' => '',
        'model' => null,
        'view_pdf_path' => 'admin.sales.pdf',
    ];

    //MÉTODOS

    public function openModal(Sale $sale)
    {

        $this->form['open'] = true;
        $this->form['document'] = 'Venta ' . $sale->serie . '-' . $sale->correlative;
        $this->form['client'] = $sale->customer->document_number . ' - ' . $sale->customer->name;
        $this->form['email'] = $sale->customer->email;
        $this->form['model'] = $sale;
    }

    public function sendEmail()
    {

        // Validar email
        $this->validate([
            'form.email' => 'required|email',
        ]);

        //Llamar a un mailable. Es necesario usar un facade
        Mail::to($this->form['email'])->send(new \App\Mail\PdfSend($this->form));



        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Correo enviado',
            'html' => 'El correo se ha enviado correctamente',
        ]);
        $this->reset('form'); //reseteamos el formulario['open'] = false;
    }
}
