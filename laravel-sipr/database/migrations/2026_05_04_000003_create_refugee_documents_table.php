<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refugee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refugee_id')->constrained('refugees')->cascadeOnDelete();
            $table->string('document_type', 100);
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->string('drive_file_id')->nullable();
            $table->string('verification_status', 50)->default('Perlu Verifikasi');
            $table->timestamp('uploaded_at')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refugee_documents');
    }
};
