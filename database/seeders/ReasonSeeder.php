<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $reasons = [
        //razones para ingresos
        [
          'name' => 'Ajuste por inventario',
          'type' => 1, // 1 = entrada, 2 = salida
        ],
        [
            'name' => 'Devolución de cliente',
            'type' => 1.
        ],
        [
            'name' => 'Producción terminada',
            'type' => 1.
        ],
        [
            'name' => 'Error en salida anterior',
            'type' => 1.
        ],
        //razones para salidas
        [
          'name' => 'Ajuste por inventario',
          'type' => 2,
        ],
        [
            'name' => 'Merma o desperdicio',
            'type' => 2,
        ],
        [
            'name' => 'Pconsumo interno',
            'type' => 2,
        ],
        [
            'name' => 'Caducidad',
            'type' => 2,
        ],
      ];

      foreach ($reasons as $reason) {
        \App\Models\Reason::create($reason);
      }
    }
}
