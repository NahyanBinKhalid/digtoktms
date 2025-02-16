<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale' => $this->faker->randomElement(['en', 'fr', 'es']), // Randomly pick a locale
            'key' => $this->faker->unique()->word . '.' . $this->faker->word, // Generate a unique key
            'content' => $this->faker->sentence, // Generate a random sentence as content
            'tags' => json_encode([$this->faker->word, $this->faker->word])
        ];
    }
}
