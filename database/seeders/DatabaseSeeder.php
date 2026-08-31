<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Oscar Martín',
            'email' => 'oscarbabe3@gmail.com',
            'password' => bcrypt('Password'),
        ]);

        //datos creados con el seeder(manualmente)
        $this->call([
            IdentitySeeder::class,
            CategorySeeder::class,
            WarehouseSeeder::class,
            ReasonSeeder::class,
        ]);

        // Crea clientes con factory
        Customer::factory(50)->create();

        // Crea proveedores con factory
        Supplier::factory(5)->create();

        //Crea productos usando el factory en lugar de un archivo seeder
        Product::factory(50)->create();
    }
}
