<?php

namespace Database\Factories;

use App\Models\ReportLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportLogFactory extends Factory
{
    protected $model = ReportLog::class;

    public function definition(): array
    {
        return [
            'report_type' => $this->faker->randomElement(['Rekap Data Aktif', 'Laporan Dokumen', 'Audit Trail', 'Prioritas Verifikasi']),
            'filter_summary' => $this->faker->sentence(),
            'downloaded_by' => null,
            'downloaded_by_name' => $this->faker->randomElement(['Admin', 'Supervisor']),
            'downloaded_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
