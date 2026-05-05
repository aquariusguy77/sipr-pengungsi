<?php

namespace Database\Factories;

use App\Models\Refugee;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefugeeFactory extends Factory
{
    protected $model = Refugee::class;

    public function definition(): array
    {
        return [
            'internal_id' => 'RDS-' . $this->faker->unique()->numerify('24###'),
            'name' => $this->faker->name(),
            'nationality' => $this->faker->randomElement(['Somalia', 'Irak', 'Afghanistan', 'Myanmar', 'Sudan', 'Yaman']),
            'unhcr_number' => 'UNHCR-' . strtoupper($this->faker->lexify('???')) . '-' . $this->faker->numerify('####'),
            'status' => $this->faker->randomElement(['Aktif', 'Verifikasi', 'Mutasi']),
            'location' => $this->faker->randomElement(['Hunian A-03', 'Hunian B-02', 'Hunian C-05', 'Transit 1']),
            'notes' => $this->faker->sentence(),
            'registered_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
