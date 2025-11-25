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
        Schema::table('sales_transaction_details', function (Blueprint $table) {
            $table->dropColumn('harga');
            $table->float('harga_pokok');
            $table->float('jual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_transaction_details', function (Blueprint $table) {
            $table->float('harga');
            $table->dropColumn('harga_pokok');
            $table->dropColumn('jual');
        });
    }
};
