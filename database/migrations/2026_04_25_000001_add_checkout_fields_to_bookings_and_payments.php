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
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('fulfillment_method', ['pickup', 'delivery'])
                ->default('pickup')
                ->after('status');
            $table->foreignId('address_id')
                ->nullable()
                ->after('fulfillment_method')
                ->constrained('addresses')
                ->nullOnDelete();
            $table->json('delivery_address_snapshot')
                ->nullable()
                ->after('address_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider', 40)
                ->default('manual_qris')
                ->after('status');
            $table->enum('payment_method', ['qris'])
                ->default('qris')
                ->after('provider');
            $table->string('invoice_number')
                ->nullable()
                ->unique()
                ->after('payment_method');
            $table->dateTime('expires_at')
                ->nullable()
                ->after('invoice_number');
            $table->dateTime('paid_at')
                ->nullable()
                ->after('expires_at');
            $table->string('proof_path')
                ->nullable()
                ->after('paid_at');
            $table->text('proof_notes')
                ->nullable()
                ->after('proof_path');
            $table->enum('proof_status', ['not_uploaded', 'uploaded', 'verified', 'rejected'])
                ->default('not_uploaded')
                ->after('proof_notes');
            $table->dateTime('proof_uploaded_at')
                ->nullable()
                ->after('proof_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'provider',
                'payment_method',
                'invoice_number',
                'expires_at',
                'paid_at',
                'proof_path',
                'proof_notes',
                'proof_status',
                'proof_uploaded_at',
            ]);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('address_id');
            $table->dropColumn([
                'fulfillment_method',
                'delivery_address_snapshot',
            ]);
        });
    }
};
