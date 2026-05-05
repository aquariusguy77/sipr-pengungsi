<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refugee_id')->constrained('refugees')->cascadeOnDelete();
            $table->string('location_name', 120);
            $table->date('entered_at')->nullable();
            $table->date('exited_at')->nullable();
            $table->string('placement_status', 50)->default('Aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};
