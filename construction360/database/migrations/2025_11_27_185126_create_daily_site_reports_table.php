<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_site_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users'); // Site supervisor
            
            // Tarih ve Hava
            $table->date('report_date');
            $table->string('weather')->nullable(); // Hava durumu
            $table->time('work_start_time')->nullable();
            $table->time('work_end_time')->nullable();
            
            // Ekip Bilgileri
            $table->integer('team_count')->default(0); // Ekip sayısı
            $table->integer('subcontractor_count')->default(0); // Taşeron sayısı
            $table->text('work_areas')->nullable(); // Çalışma alanları
            
            // İş ve Malzeme (JSON olarak saklanabilir)
            $table->json('work_items')->nullable(); // Yapılan iş kalemleri
            $table->json('materials_used')->nullable(); // Kullanılan malzemeler
            
            // Notlar
            $table->text('summary')->nullable(); // Özet
            $table->text('obstacles')->nullable(); // Risk/engel notları
            $table->json('safety_checklist')->nullable(); // İSG checklist
            
            // Onay
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_site_reports');
    }
};