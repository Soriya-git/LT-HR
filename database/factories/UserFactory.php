<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** Extends default user factory with HR fields + users_txid. */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => bcrypt('Password@123'),
            'remember_token'    => Str::random(10),

            'users_txid'        => (string) Str::uuid(),
            'phone'             => $this->faker->phoneNumber(),
            'department'        => $this->faker->randomElement(['HR','Operations','Sales','IT']),
            'position'          => $this->faker->jobTitle(),
            'hire_date'         => $this->faker->date(),
            'salary_monthly'    => $this->faker->randomFloat(2, 300, 3000),
            'employment_status' => 'active',
            'manager_id'        => null,
        ];
    }
}