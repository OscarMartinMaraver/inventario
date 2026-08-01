<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class ProductTable extends DataTableComponent
{
    /**Para evitar el problema N+1 (Cada vez que quiero acceder a la categoria del producto, o la imagen del producto (la imagen se trae mediante un accesor del modelo Product), se hace una consulta, es decir, en este caso 100 consultas), necesito cargar
     * previamente el modelo Product::class. Por ello en lugar de hacerlo con Protected $model = Product::class, uso la función Builder del final.
     * Así hace una consulta para traer los productos y otra consulta para traer las relaciones con categoria
     */
    // protected $model = Product::class;

    // Indico nombre de la tabla para evitar problemas al traducir las columnas
    public string $tableName = 'products_table';


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
            ImageColumn::make("Imagen")
            ->location(
                fn($row)=>$row->image
            )->attributes(
                fn($row)=>[
                    // 'class' => 'w-20 h-20 object-cover object-center rounded-md'
                    'class' => 'image-product'
                ]
            ),
            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),
            Column::make("Categoria", "category.name") //Para acceder a la relacion con la categoria del producto uso category.name en lugar de category->name
                ->sortable()
                ->searchable(),
            Column::make("Precio", "price")
                ->sortable(),
            Column::make("Acciones")
            ->label(function($row){
                return view('admin.products.actions', ['product' => $row]);
            })
        ];
    }

    //para evitar el problema N+1, cargo las relaciones con la función builder
    public function builder(): Builder
    {
        return Product::query()->with('category', 'images');
    }
}
