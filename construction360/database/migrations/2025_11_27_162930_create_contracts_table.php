<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique(); // Sözleşme no (SOZ-2025-0001)
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->integer('version')->default(1); // Versiyon
            
            // Sözleşme Bilgileri
            $table->string('title'); // Başlık
            $table->text('terms')->nullable(); // Şartlar (rich text)
            $table->text('template_content')->nullable(); // Şablon içeriği
            
            // Finansal Bilgiler
            $table->decimal('contract_amount', 15, 2)->default(0); // Sözleşme bedeli
            $table->decimal('advance_amount', 15, 2)->default(0); // Avans tutarı
            $table->decimal('retention_amount', 15, 2)->default(0); // Teminat tutarı
            $table->string('currency', 3)->default('TRY');
            
            // Tarih Bilgileri
            $table->date('contract_date')->nullable(); // Sözleşme tarihi
            $table->date('start_date')->nullable(); // Başlangıç tarihi
            $table->date('end_date')->nullable(); // Bitiş tarihi
            
            // Dosyalar
            $table->string('signed_file_path')->nullable(); // İmzalı PDF yolu
            $table->timestamp('signed_at')->nullable(); // İmza tarihi
            
            // Meta
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};