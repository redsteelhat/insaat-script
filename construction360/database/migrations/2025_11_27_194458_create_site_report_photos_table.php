<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_report_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_site_report_id')->constrained()->cascadeOnDelete();
            $table->string('file_path'); // Dosya yolu
            $table->string('file_name')->nullable(); // Orijinal dosya adı
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable(); // Byte cinsinden
            $table->text('caption')->nullable(); // Açıklama
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_report_photos');
    }
};