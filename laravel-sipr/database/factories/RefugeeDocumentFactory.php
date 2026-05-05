<?php

namespace Database\Factories;

use App\Models\Refugee;
use App\Models\RefugeeDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefugeeDocumentFactory extends Factory
{
    protected $model = RefugeeDocument::class;

    public function definition(): array
    {
        return [
            'refugee_id' => Refugee::factory(),
            'document_type' => $this->faker->randomElement(['Identitas Utama', 'Administrasi Internal', 'Riwayat Penempatan', 'Lampiran Tambahan']),
            'file_name' => $this->faker->bothify('document-###.pdf'),
            'file_path' => 'folder/pengungsi/' . $this->faker->slug() . '.pdf',
            'drive_file_id' => $this->faker->uuid(),
            'verification_status' => $this->faker->randomElement(['Lengkap', 'Perlu Verifikasi', 'Belum Lengkap']),
            'uploaded_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'uploaded_by' => null,
            'notes' => $this->faker->sentence(),
        ];
    }
}
