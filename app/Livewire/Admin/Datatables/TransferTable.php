<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class TransferTable extends DataTableComponent
{
    /**
     * * Reemplazamos la propiedad "$model = Transfer::class" por el método builder()
     * para realizar "Eager Loading" (carga ambiciosa) de la relación 'originwarehouse' y 'destinationwarehouse'.
     *
     * Esto evita el problema de N+1 consultas: sin el "with('originwarehouse', 'destinationwarehouse')", Laravel realizaría 
     * 1 consulta para traer las N Transferencias y luego N consultas individuales más 
     * para obtener el almacén origen y destino. Con este método, todo se resuelve en solo 2 consultas.
     */

    //protected $model = Transfer::class;

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
            Column::make("Almacén origen", "originwarehouse.name")
                ->sortable()
                ->searchable(),
            Column::make("Almacén destino", "destinationwarehouse.name")
                ->sortable()
                ->searchable(),
            Column::make("Total", "total")
                ->sortable()
                ->format(fn($value) => '€/ ' . number_format($value, 2, '.', ',')),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.transfers.actions', ['transfer' => $row]);
                    //Con format() capturamos un valor de una celda y lo formateamos
                    //Con label() capturamos la fila completa para manipularla. Solo llama los valores que hemos definido en las columnas, si hay algo que no se muestra, 
                    //habría que añadirlo como algo adicional en la funcion configure()
                })
        ];
    }

    //para evitar el problema N+1, cargo las relaciones con la función builder
    public function builder(): Builder
    {
        return Transfer::query()->with([
            'originwarehouse',
            'destinationwarehouse',
            ]);
    }

    
    //PROPIEDADES

    public $form = [
        'open' => false,
        'document' => '',
        'client' => '',
        'email' => '',
        'model' => null,
        'view_pdf_path' => 'admin.transfers.pdf',
    ];

    //MÉTODOS

    public function openModal(Transfer $transfer)
    {

        $this->form['open'] = true;
        $this->form['document'] = 'Transferencia ' . $transfer->serie . '-' . $transfer->correlative;
        $this->form['client'] = 'Origen: ' .  $transfer->originWarehouse->name . ' - Destino: ' . $transfer->destinationWarehouse->name;
        $this->form['email'] = '';
        $this->form['model'] = $transfer;
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
