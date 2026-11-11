<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Estratégia de migração compatível com o estado do banco:
     * garante que 'address' em 'users' seja nullable para o cadastro público
     * e que a tabela 'profile' exista com as colunas esperadas pelo model Profile.
     */
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('address')->nullable()->change();
            });
        }

        if (! Schema::hasTable('profile')) {
            Schema::create('profile', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('user');
                $table->string('description')->nullable();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        } else {
            Schema::table('profile', function (Blueprint $table) {
                if (Schema::hasColumn('profile', 'description')) {
                    $table->string('description')->nullable()->change();
                }
            });
        }
    }

    public function down(): void
    {
        //
    }
};
