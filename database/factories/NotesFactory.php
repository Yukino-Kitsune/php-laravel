<?php

namespace Database\Factories;

use App\Models\NotesModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NotesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = NotesModel::class;
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'company' => $this->faker->company(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'birthday' => $this->faker->date(),
            'photo' => $this->faker->file('./storage/app/random_images', './storage/app/photos', false),
        ];
    }
}
