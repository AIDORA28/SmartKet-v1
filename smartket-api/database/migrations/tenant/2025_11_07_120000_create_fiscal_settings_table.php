<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiscal_settings', function (Blueprint $table) {
            $table->id();
            $table->char('ruc', 11)->nullable();
            $table->string('razon_social')->nullable();
            $table->string('comprobante_default')->nullable(); // boleta|factura
            $table->boolean('boleta_simple_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_settings');
    }
};

