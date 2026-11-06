<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A tela de edição do painel usa as linguagens do usuário.
        if (Schema::hasTable('user_languages')) {
            return;
        }

        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();
            $table->string('language');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_languages');
    }
};
