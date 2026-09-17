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
        Schema::create('product_itens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('quantidade');
            $table->string('cor');
            $table->decimal('valor', 10, 2);
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_itens');
    }
};
