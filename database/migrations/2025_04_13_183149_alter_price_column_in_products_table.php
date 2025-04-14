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
        Schema::table('products', function (Blueprint $table) {
            // Naikkan batas price jadi bisa tampung angka lebih besar
            $table->decimal('price', 10, 2)->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Balikkan ke default Laravel: decimal(8, 2)
            $table->decimal('price', 8, 2)->unsigned()->change();
        });
    }
};
