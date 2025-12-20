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
        Schema::create('opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opname_id')->constrained('opnames')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->float('harga_pokok');
            $table->integer('jumlah_awal');
            $table->integer('jumlah_opname');
            $table->integer('selisih');
            $table->integer('total_selisih');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opname_details');
    }
};
