<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A migration original criou profile.description, mas o model e o controller
 * usam "address". Em vez de editar a migration já aplicada, esta corrige o
 * schema de forma compatível com qualquer estado do banco.
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

        if (! Schema::hasColumn('profile', 'address')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->string('address')->nullable();
            });

            return;
        }

        // O controller aceita endereço vazio (nullable).
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
