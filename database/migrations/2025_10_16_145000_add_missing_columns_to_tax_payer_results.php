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
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('tax_payer_results', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('tax_payer_results', 'user')) {
                $table->string('user')->nullable();
            }
            if (!Schema::hasColumn('tax_payer_results', 'gift_name')) {
                $table->string('gift_name')->nullable();
            }
            if (!Schema::hasColumn('tax_payer_results', 'upload_order')) {
                $table->unsignedInteger('upload_order')->default(0)->comment('Order of row in uploaded file');
            }
            if (!Schema::hasColumn('tax_payer_results', 'upload_batch_id')) {
                $table->string('upload_batch_id')->nullable()->comment('Batch ID to group uploads');
            }
        });

        // Add indexes
        Schema::table('tax_payer_results', function (Blueprint $table) {
            if (!Schema::hasIndex('tax_payer_results', 'upload_batch_id')) {
                $table->index('upload_batch_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_payer_results', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('tax_payer_results', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('tax_payer_results', 'user')) {
                $table->dropColumn('user');
            }
            if (Schema::hasColumn('tax_payer_results', 'gift_name')) {
                $table->dropColumn('gift_name');
            }
            if (Schema::hasColumn('tax_payer_results', 'upload_order')) {
                $table->dropColumn('upload_order');
            }
            if (Schema::hasColumn('tax_payer_results', 'upload_batch_id')) {
                $table->dropColumn('upload_batch_id');
            }
        });
    }
};
