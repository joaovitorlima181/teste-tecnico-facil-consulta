<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //Falso cpf
        $cpf = rand(10000000000, 99999999999);
        return [
            'nome' => fake()->name(),
            'cpf' => $cpf,
            'celular' => fake()->phoneNumber(),
        ];
    }
}
