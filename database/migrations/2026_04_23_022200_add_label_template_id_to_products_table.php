<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('label_template_id')->nullable()->after('qr_code_path');
            $table->foreign('label_template_id')->references('id')->on('label_templates')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['label_template_id']);
            $table->dropColumn('label_template_id');
        });
    }
};