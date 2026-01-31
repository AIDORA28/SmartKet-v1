<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('status'); // pendiente, atendido, pagado
            $table->unsignedBigInteger('mesa_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->timestamps();

            $table->foreign('mesa_id')->references('id')->on('mesas')->onDelete('set null');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

