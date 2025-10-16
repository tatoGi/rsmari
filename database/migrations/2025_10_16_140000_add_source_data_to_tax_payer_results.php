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
        Schema::table('tax_payer_results', function (Blueprint $table) {
            $table->string('name')->nullable()->after('raw_response');
            $table->string('user')->nullable()->after('name');
            $table->string('gift_name')->nullable()->after('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_payer_results', function (Blueprint $table) {
            $table->dropColumn(['name', 'user', 'gift_name']);
        });
    }
};
