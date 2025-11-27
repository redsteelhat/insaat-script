<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->integer('sort_order')->default(0); // Sıralama
            
            // İş Kalemi Bilgileri
            $table->string('code')->nullable(); // İş kalemi kodu
            $table->text('description'); // Açıklama
            $table->string('unit')->nullable(); // Birim (m², m³, adet, kg, vb.)
            $table->decimal('quantity', 10, 3)->default(1); // Miktar
            
            // Fiyat Bilgileri
            $table->decimal('unit_price', 15, 2)->default(0); // Birim fiyat
            $table->decimal('material_cost', 15, 2)->default(0); // Malzeme maliyeti
            $table->decimal('labor_cost', 15, 2)->default(0); // İşçilik maliyeti
            $table->decimal('overhead_cost', 15, 2)->default(0); // Genel gider
            $table->decimal('profit_margin', 5, 2)->default(0); // Kâr marjı (%)
            $table->decimal('total_price', 15, 2)->default(0); // Toplam fiyat (quantity * unit_price)
            
            // Kategori
            $table->string('category')->nullable(); // Kategori/Ana başlık
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_items');
    }
};