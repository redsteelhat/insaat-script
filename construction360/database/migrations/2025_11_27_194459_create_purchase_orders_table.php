<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // PO no (PO-2025-0001)
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            
            // Sipariş Bilgileri
            $table->date('order_date');
            $table->date('delivery_date')->nullable(); // Teslim tarihi
            $table->text('delivery_address')->nullable(); // Sevk adresi
            
            // Finansal
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('TRY');
            
            // Durum
            $table->enum('status', ['draft', 'sent', 'delivered', 'invoiced', 'paid', 'cancelled'])->default('draft');
            
            // Notlar
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            
            // Meta
            $table->foreignId('created_by')->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};