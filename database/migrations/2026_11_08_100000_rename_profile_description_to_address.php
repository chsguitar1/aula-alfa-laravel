<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A migration original da tabela "profile" criou a coluna "description".
 * O sistema (model, controller e views) usa "address".
 *
 * Em vez de editar a migration antiga (que já pode ter rodado em outros bancos),
 * esta migration nova corrige o schema e só mexe no que ainda não está certo.
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

        // O formulário aceita endereço vazio (nullable), então a coluna precisa aceitar NULL.
        if (Schema::hasColumn('profile', 'address')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->string('address')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('profile', 'address') && ! Schema::hasColumn('profile', 'description')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->renameColumn('address', 'description');
            });
        }
    }
};
