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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('snap_token', 120)
                ->nullable()
                ->after('invoice_number');
            $table->string('gateway_transaction_id', 100)
                ->nullable()
                ->after('snap_token');
            $table->string('gateway_payment_type', 50)
                ->nullable()
                ->after('payment_method');
            $table->string('gateway_status', 40)
                ->nullable()
                ->after('status');
            $table->dateTime('gateway_last_synced_at')
                ->nullable()
                ->after('gateway_status');
            $table->json('gateway_payload')
                ->nullable()
                ->after('gateway_last_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'gateway_transaction_id',
                'gateway_payment_type',
                'gateway_status',
                'gateway_last_synced_at',
                'gateway_payload',
            ]);
        });
    }
};
