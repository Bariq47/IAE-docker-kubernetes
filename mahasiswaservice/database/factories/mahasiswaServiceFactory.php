<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\mahasiswaService>
 */
class mahasiswaServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nim' => $this->faker->unique()->randomNumber(8),
            'nama' => $this->faker->name(),
            'umur' => $this->faker->numberBetween(18, 30),
            'email' => $this->faker->unique()->safeEmail(),
            'alamat' => $this->faker->address(),
            'nomor' => $this->faker->phoneNumber(),
        ];
    }
}
