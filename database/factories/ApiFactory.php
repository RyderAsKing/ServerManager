<?php

namespace Database\Factories;

use App\Models\Api;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Api::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'nick' => $this->faker->name(),
            'type' => 0,
            'api' => Str::random(16),
            'api_pass' => Str::random(32),
        ];
    }
}
