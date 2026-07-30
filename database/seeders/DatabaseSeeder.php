<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
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

        $this->call([
            CategorySeeder::class
            ]);

        //Crea productos usando el factory en lugar de un archivo seeder
        Product::factory(100)->create();    

    }
}
