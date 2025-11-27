<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Malzeme kodu
            $table->string('name'); // Malzeme adı
            $table->foreignId('category_id')->nullable()->constrained('material_categories')->nullOnDelete();
            $table->string('unit')->nullable(); // Birim (m², m³, adet, kg, vb.)
            
            // Stok Bilgileri
            $table->decimal('min_stock', 10, 3)->default(0); // Min stok seviyesi
            $table->decimal('current_stock', 10, 3)->default(0); // Mevcut stok
            
            // Maliyet Bilgileri
            $table->decimal('last_purchase_price', 15, 2)->nullable(); // Son alış fiyatı
            $table->decimal('average_cost', 15, 2)->nullable(); // Ortalama maliyet
            
            // Durum
            $table->boolean('is_active')->default(true);
            
            // Notlar
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};