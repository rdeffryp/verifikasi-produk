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
            $table->increments('id');
            $table->string('product_id', 50)->unique();
            $table->string('nama_produk', 100);
            $table->date('tanggal_produksi');
            $table->date('tanggal_kadaluarsa');
            $table->string('batch_number', 50)->nullable();
            $table->string('hash_data', 64);
            $table->text('signature');
            $table->boolean('is_verified')->default(0);
            $table->timestamp('first_scan_at')->nullable();
            $table->timestamp('last_scan_at')->nullable();
            $table->unsignedInteger('scan_count')->default(0);
            $table->string('qr_code_path', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
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
