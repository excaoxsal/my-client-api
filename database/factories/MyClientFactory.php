<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MyClient;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MyClient>
 */
class MyClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->company,
            'slug' => Str::slug($this->faker->unique()->company),
            'is_project' => '0',
            'self_capture' => '1',
            'client_prefix' => strtoupper(Str::random(4)),
            'client_logo' => 'no-image.jpg',
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}
