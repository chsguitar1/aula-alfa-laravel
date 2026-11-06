<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Corrige a evolução do campo de endereço do perfil.
 *
 * A migration original de rename (aula 21/10) apontava para a tabela "profiles",
 * mas a tabela real criada em 2026_08_27_000637 é "profile". Em vez de editar
 * migrations já aplicadas/compartilhadas, esta nova migration renomeia a coluna
 * somente quando o banco ainda está no estado antigo, sendo segura para bancos
 * novos e para bancos que já tenham a coluna "address".
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

        // O endereço é opcional no formulário de perfil.
        Schema::table('profile', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
        });
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
