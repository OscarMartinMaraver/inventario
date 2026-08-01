<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
            'identity_id'=>4,
            'document_number'=>$this->faker->unique()->numerify('###########'),
            'name'=>$this->faker->company(),
            'address'=>$this->faker->address(),
            'email'=>$this->faker->unique()->safeemail(),
            'phone'=>$this->faker->phoneNumber(),
        ];
    }
}
