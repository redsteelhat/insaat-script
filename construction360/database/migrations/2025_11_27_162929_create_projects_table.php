<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique(); // Proje kodu (PRJ-2025-0001)
            $table->string('name'); // Proje adı
            
            // İlişkiler
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete(); // Şantiye lokasyonu
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete(); // Müşteri (client role)
            
            // Proje Bilgileri
            $table->enum('project_type', ['konut', 'ticari', 'endustriyel', 'tadilat', 'diger'])->default('konut');
            $table->text('description')->nullable();
            $table->decimal('area_m2', 10, 2)->nullable(); // Toplam alan (m²)
            $table->string('location_city')->nullable(); // İl
            $table->string('location_district')->nullable(); // İlçe
            $table->text('location_address')->nullable(); // Tam adres
            
            // Tarih Bilgileri
            $table->date('start_date')->nullable(); // Başlangıç tarihi
            $table->date('planned_end_date')->nullable(); // Planlanan bitiş tarihi
            $table->date('actual_end_date')->nullable(); // Gerçek bitiş tarihi
            
            // Finansal Bilgiler
            $table->decimal('contract_amount', 15, 2)->default(0); // Sözleşme bedeli
            $table->decimal('budget_amount', 15, 2)->default(0); // Bütçe
            $table->decimal('actual_cost', 15, 2)->default(0); // Gerçekleşen maliyet
            $table->string('currency', 3)->default('TRY');
            
            // Durum Yönetimi
            $table->enum('status', ['planned', 'in_progress', 'on_hold', 'completed', 'handed_over', 'cancelled'])->default('planned');
            $table->integer('progress_percentage')->default(0); // İlerleme yüzdesi
            
            // Ödeme Planı (JSON)
            $table->json('payment_schedule')->nullable();
            
            // Meta
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};