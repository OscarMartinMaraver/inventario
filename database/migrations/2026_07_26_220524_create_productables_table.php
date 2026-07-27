<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->morphs('productable'); //El metodo morphs permite crear una relacion polimorfica. La tabla productables tendra una columna productable_id y otra productable_type. Relaciona los produtos con todos los tipos de transacciones. VER VIDEO RELACIONES POLIMÓRFICAS
            $table->integer('quantity');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productables');
    }
};
