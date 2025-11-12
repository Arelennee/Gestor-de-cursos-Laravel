<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Leccion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leccion>
 */
class LeccionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Leccion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'curso_id' => Curso::factory(),
            'titulo' => fake()->sentence(4),
            'contenido' => fake()->paragraphs(3, true),
            'video_url' => fake()->optional()->url(),
            'orden' => fake()->numberBetween(1, 20),
        ];
    }
}
