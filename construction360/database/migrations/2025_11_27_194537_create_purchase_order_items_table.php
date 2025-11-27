<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('description'); // Açıklama
            $table->string('unit')->nullable(); // Birim
            $table->decimal('quantity', 10, 3)->default(1); // Miktar
            $table->decimal('unit_price', 15, 2)->default(0); // Birim fiyat
            $table->decimal('total_price', 15, 2)->default(0); // Toplam fiyat
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};