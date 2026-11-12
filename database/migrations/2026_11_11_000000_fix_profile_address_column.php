<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nova migration (a migration original de `profile` não é alterada): o schema criado
// usa a coluna `description` NOT NULL, mas model e telas usam `address` opcional.
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
        if (Schema::hasColumn('profile', 'address') && ! Schema::hasColumn('profile', 'description')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->renameColumn('address', 'description');
            });
        }
    }
};
