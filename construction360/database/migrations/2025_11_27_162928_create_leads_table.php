<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_number')->unique(); // Talep numarası (TAL-2025-0001)
            $table->string('name'); // Ad Soyad / Firma
            $table->string('phone'); // Telefon (zorunlu, TR format)
            $table->string('email')->nullable(); // E-posta (opsiyonel)
            $table->string('company')->nullable(); // Firma adı (opsiyonel)
            
            // Proje Bilgileri
            $table->enum('project_type', ['konut', 'ticari', 'endustriyel', 'tadilat'])->nullable(); // Proje türü
            $table->string('location_city')->nullable(); // İl
            $table->string('location_district')->nullable(); // İlçe
            $table->string('location_address')->nullable(); // Tam adres
            
            // Proje Detayları
            $table->decimal('area_m2', 10, 2)->nullable(); // Yaklaşık m²
            $table->integer('room_count')->nullable(); // Oda sayısı
            $table->integer('floor_count')->nullable(); // Kat sayısı
            $table->enum('current_status', ['arsa_var', 'proje_var', 'kaba_var', 'tadilat'])->nullable(); // Mevcut durum
            $table->json('requested_services')->nullable(); // İstenen hizmetler (mimari proje, iç mimari, taahhüt, anahtar teslim)
            $table->string('budget_range')->nullable(); // Bütçe aralığı
            
            // Talep Bilgileri
            $table->enum('source', ['web', 'telefon', 'referral', 'sosyal_medya', 'diger'])->default('web'); // Kaynak
            $table->text('message')->nullable(); // Mesaj / ihtiyaç detayı
            $table->date('requested_site_visit_date')->nullable(); // İstenen keşif tarihi
            
            // Durum Yönetimi
            $table->enum('status', ['new', 'contacted', 'site_visit_planned', 'quoted', 'won', 'lost'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Atanan kişi
            
            // KVKK
            $table->boolean('kvkk_consent')->default(false); // KVKK onayı
            
            // Meta
            $table->text('notes')->nullable(); // İç notlar
            $table->timestamp('contacted_at')->nullable(); // İletişime geçilme tarihi
            $table->timestamp('site_visit_at')->nullable(); // Keşif tarihi
            $table->timestamp('quoted_at')->nullable(); // Teklif verilme tarihi
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};