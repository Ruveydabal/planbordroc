<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('link');
        });

        // Initialiseer sort_order op basis van aanmaakvolgorde
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('UPDATE portfolios SET sort_order = id');
        } else {
            // Fallback voor andere drivers
            $portfolios = DB::table('portfolios')->select('id')->orderBy('id')->get();
            $order = 1;
            foreach ($portfolios as $p) {
                DB::table('portfolios')->where('id', $p->id)->update(['sort_order' => $order++]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};


