<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * O model User tem a relação languages() e a tela de edição usa essa tabela,
 * mas a migration dela não existia nesta branch.
 */
return new class extends Migration
{
    public function up(): void
    {
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
