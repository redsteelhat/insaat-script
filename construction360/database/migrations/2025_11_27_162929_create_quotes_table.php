<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique(); // Teklif no (TEK-2025-0001)
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('version')->default(1); // Revizyon versiyonu (v1, v2, v3)
            
            // Müşteri Bilgileri
            $table->string('client_name'); // Müşteri adı
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            
            // Teklif Bilgileri
            $table->string('title'); // Teklif başlığı
            $table->text('description')->nullable(); // Açıklama
            
            // Finansal Bilgiler
            $table->decimal('subtotal', 15, 2)->default(0); // Ara toplam
            $table->decimal('discount_amount', 15, 2)->default(0); // İskonto tutarı
            $table->decimal('discount_percentage', 5, 2)->default(0); // İskonto yüzdesi
            $table->decimal('tax_amount', 15, 2)->default(0); // KDV tutarı
            $table->decimal('tax_percentage', 5, 2)->default(18); // KDV oranı (varsayılan %18)
            $table->decimal('total_amount', 15, 2)->default(0); // Toplam tutar
            $table->string('currency', 3)->default('TRY'); // Para birimi
            
            // Durum Yönetimi
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'contracted'])->default('draft');
            $table->timestamp('sent_at')->nullable(); // Gönderilme tarihi
            $table->timestamp('approved_at')->nullable(); // Onay tarihi
            $table->timestamp('valid_until')->nullable(); // Geçerlilik süresi
            
            // Meta
            $table->text('notes')->nullable(); // İç notlar
            $table->text('terms')->nullable(); // Şartlar ve koşullar
            $table->foreignId('created_by')->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};