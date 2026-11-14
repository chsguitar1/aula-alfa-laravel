<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A migration original (2026_08_27_000637) criou profile.description (NOT NULL),
 * mas o model Profile e o formulário usam "address" (opcional).
 *
 * Em vez de editar a migration já aplicada/compartilhada, esta nova migration
 * corrige o schema de forma compatível: só renomeia se ainda existir "description"
 * e só então torna a coluna anulável. Bancos que já tenham "address" não quebram.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('profile', 'description') && ! Schema::hasColumn('profile', 'address')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->renameColumn('description', 'address');
            });
        }

        Schema::table('profile', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->renameColumn('address', 'description');
        });
    }
};
