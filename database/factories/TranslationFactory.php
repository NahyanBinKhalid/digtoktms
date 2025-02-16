<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    public function definition(): array
    {
        $locales = ['en', 'fr', 'es', 'ar'];

        static $counter = 1;
        $baseKey = 'key' . $counter;
        $counter++;

        return [
            'locale'  => $locale = Arr::random($locales),
            'key'     => "{$baseKey}.{$locale}",
            'content' => fake()->sentence(),
            'tags'    => json_encode(array_values(Arr::random(['web', 'mobile'], rand(1, 2))))
        ];
    }
}
