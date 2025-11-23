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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['barang', 'jasa']);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('nama');
            $table->string('barcode');
            $table->foreignId('distributor_id')->nullable()->constrained('distributors')->onDelete('set null');
            $table->float('harga_pokok')->nullable();
            $table->float('harga_jual')->nullable();
            $table->integer('stok')->nullable();
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
