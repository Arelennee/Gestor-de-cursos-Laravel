<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso>
 */
class CursoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Curso::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titulo' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'categoria' => fake()->randomElement(['programacion', 'diseño', 'marketing', 'negocios', 'tecnologia']),
            'duracion' => fake()->numberBetween(1, 100),
            'nivel' => fake()->randomElement(['principiante', 'intermedio', 'avanzado']),
        ];
    }
}
