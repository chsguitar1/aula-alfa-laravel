<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nova migration (não altera a migration já compartilhada) e segura:
// só renomeia se a coluna antiga ainda existir na tabela real `profile`.
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('profile', 'description') && ! Schema::hasColumn('profile', 'address')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->renameColumn('description', 'address');
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
