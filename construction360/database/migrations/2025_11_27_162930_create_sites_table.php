<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Şantiye adı
            $table->text('address'); // Adres
            $table->string('city')->nullable(); // İl
            $table->string('district')->nullable(); // İlçe
            $table->string('postal_code')->nullable(); // Posta kodu
            
            // Koordinatlar
            $table->decimal('latitude', 10, 8)->nullable(); // Enlem
            $table->decimal('longitude', 11, 8)->nullable(); // Boylam
            
            // İletişim
            $table->string('contact_person')->nullable(); // İrtibat kişisi
            $table->string('contact_phone')->nullable(); // İrtibat telefonu
            $table->string('contact_email')->nullable(); // İrtibat e-postası
            
            // Durum
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};