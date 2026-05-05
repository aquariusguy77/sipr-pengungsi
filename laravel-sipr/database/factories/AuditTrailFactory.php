<?php

namespace Database\Factories;

use App\Models\AuditTrail;
use App\Models\Refugee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditTrailFactory extends Factory
{
    protected $model = AuditTrail::class;

    public function definition(): array
    {
        return [
            'refugee_id' => Refugee::factory(),
            'field_name' => $this->faker->randomElement(['status', 'location', 'verification_status']),
            'old_value' => $this->faker->word(),
            'new_value' => $this->faker->word(),
            'action_label' => $this->faker->sentence(3),
            'performed_by_name' => $this->faker->randomElement(['Admin', 'Petugas Pendataan', 'Supervisor']),
            'performed_by' => null,
            'reason' => $this->faker->sentence(),
            'performed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
