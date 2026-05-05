<?php

namespace Database\Factories;

use App\Models\Placement;
use App\Models\Refugee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlacementFactory extends Factory
{
    protected $model = Placement::class;

    public function definition(): array
    {
        return [
            'refugee_id' => Refugee::factory(),
            'location_name' => $this->faker->randomElement(['Hunian A-03', 'Hunian B-02', 'Hunian C-05', 'Transit 1']),
            'entered_at' => $this->faker->date(),
            'exited_at' => null,
            'placement_status' => $this->faker->randomElement(['Aktif', 'Mutasi', 'Transit']),
            'notes' => $this->faker->sentence(),
        ];
    }
}
