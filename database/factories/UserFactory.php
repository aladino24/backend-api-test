<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'age' => $this->faker->numberBetween(18, 70),
            'membership_status' => $this->faker->randomElement(['active', 'inactive']),
            'password' => Hash::make('password123'), // default password
        ];
    }

    public function withRoles()
    {
        return $this->afterCreating(function ($user) {
            // Assign random roles to the user
            $roles = Role::inRandomOrder()->take(rand(1, 3))->get(); // 1 to 3 random roles
            $user->roles()->attach($roles);
        });
    }
}
