<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable(); // Tedarikçi kodu
            $table->string('name'); // Firma adı
            $table->string('tax_number')->nullable(); // Vergi no
            $table->string('tax_office')->nullable(); // Vergi dairesi
            
            // İletişim
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Adres
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            
            // Kategori
            $table->string('category')->nullable(); // beton, demir, seramik, elektrik, mekanik vb.
            
            // Durum
            $table->boolean('is_active')->default(true);
            
            // Notlar
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};