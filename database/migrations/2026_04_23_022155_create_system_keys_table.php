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
        Schema::create('system_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key_name', 50)->unique();
            $table->text('public_key');
            $table->string('algorithm', 20)->default('ECDSA-secp256k1');
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_keys');
    }
};
