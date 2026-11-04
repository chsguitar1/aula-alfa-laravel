<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * O endereço nasceu como "description" NOT NULL e foi renomeado para "address", mas o
     * formulário de perfil o trata como opcional (nullable). Nova migration (sem mexer nas
     * já aplicadas) para alinhar o schema à validação.
     */
    public function up(): void
    {
        if (Schema::hasColumn('profile', 'address')) {
            Schema::table('profile', function (Blueprint $table) {
                $table->string('address')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('profile', 'address')) {
            DB::table('profile')->whereNull('address')->update(['address' => '']);

            Schema::table('profile', function (Blueprint $table) {
                $table->string('address')->nullable(false)->change();
            });
        }
    }
};
