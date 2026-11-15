<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A migration original (2026_08_27_000637) criou profile.description (obrigatória),
 * mas o model, o controller e a tela de edição usam "address" (opcional).
 *
 * Em vez de editar a migration já aplicada/compartilhada, esta migration corrige o schema
 * de forma compatível com bancos em qualquer um dos dois estados (com "description" ou já com "address").
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

        if (Schema::hasColumn('profile', 'address')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->string('address')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // A coluna volta ao nome original; ela permanece opcional para não falhar com linhas sem endereço.
        if (Schema::hasColumn('profile', 'address') && ! Schema::hasColumn('profile', 'description')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->renameColumn('address', 'description');
            });
        }
    }
};
