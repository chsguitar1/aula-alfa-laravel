<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A tabela criada em 2026_08_27_000637 chama-se "profile" (singular), mas esta migration
     * apontava para "profiles", o que quebrava um "migrate" em banco novo. Ela agora localiza
     * a tabela real e só age se ainda houver a coluna antiga. Bancos que já aplicaram esta
     * migration não são afetados (ela não é executada de novo).
     */
    private function table(): ?string
    {
        foreach (['profile', 'profiles'] as $name) {
            if (Schema::hasTable($name)) {
                return $name;
            }
        }

        return null;
    }

    public function up(): void
    {
        $name = $this->table();

        if ($name && Schema::hasColumn($name, 'description') && ! Schema::hasColumn($name, 'address')) {
            Schema::table($name, function (Blueprint $table) {
                $table->renameColumn('description', 'address');
            });
        }
    }

    public function down(): void
    {
        $name = $this->table();

        if ($name && Schema::hasColumn($name, 'address') && ! Schema::hasColumn($name, 'description')) {
            Schema::table($name, function (Blueprint $table) {
                $table->renameColumn('address', 'description');
            });
        }
    }
};
