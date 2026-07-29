<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrónicos',
                'description' => 'Artículos electrónicos'
            ],
            [
                'name' => 'Ropa',
                'description' => 'Articulos de ropa'
            ],
            [
                'name' => 'Alimentos',
                'description' => 'Articulos de alimentos'
            ],
            [
                'name' => 'Hogar',
                'description' => 'Articulos de hogar'
            ],
            [
                'name' => 'Juguetes',
                'description' => 'Articulos de jugetes'
            ],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
