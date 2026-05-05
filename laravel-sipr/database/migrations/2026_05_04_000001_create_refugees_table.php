<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refugees', function (Blueprint $table) {
            $table->id();
            $table->string('internal_id')->unique();
            $table->string('name');
            $table->string('nationality', 100);
            $table->string('unhcr_number')->nullable();
            $table->string('status', 50);
            $table->string('location', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refugees');
    }
};
