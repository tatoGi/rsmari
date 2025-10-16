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
        Schema::create('tax_payer_results', function (Blueprint $table) {
            $table->id();
            $table->string('ident_code')->index();
            $table->string('status')->nullable();
            $table->string('registered_subject')->nullable();
            $table->string('full_name')->nullable();
            $table->string('start_date')->nullable();
            $table->string('vat_payer')->nullable();
            $table->text('mortgage')->nullable();
            $table->text('sequestration')->nullable();
            $table->string('additional_status')->nullable();
            $table->string('non_resident')->nullable();
            $table->enum('response_status', ['success', 'error'])->default('error');
            $table->text('error_message')->nullable();
            $table->longText('raw_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_payer_results');
    }
};
