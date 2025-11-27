<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_number')->unique(); // Issue no (ISS-2025-0001)
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users'); // Oluşturan
            
            // Issue Bilgileri
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['kalite', 'is_guvenligi', 'tedarik', 'tasarim', 'musteri', 'diger'])->default('diger');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Durum Yönetimi
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('due_date')->nullable(); // SLA/son tarih
            
            // Lokasyon
            $table->string('location')->nullable(); // Konum/detay
            
            // Fotoğraflar (JSON array of file paths)
            $table->json('photos')->nullable();
            
            // Çözüm
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};