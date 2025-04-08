<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pokemon_cards', function (Blueprint $table) {
            $table->integer('inventory_total_qty')->default(1);
            $table->integer('checked_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemon_cards', function (Blueprint $table) {
            $table->dropColumn('inventory_total_qty');
            $table->dropColumn('checked_qty');
        });
    }
};