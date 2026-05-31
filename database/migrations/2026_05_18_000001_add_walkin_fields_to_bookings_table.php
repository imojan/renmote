<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Where the booking originated from: "online" (regular flow via Midtrans)
            // or "walk_in" (vendor manually records an off-platform booking).
            $table->string('source', 20)->default('online')->after('status');

            // For walk-in bookings the customer is not necessarily a registered user.
            // We capture their name + phone purely for vendor reference. user_id may
            // still point at the vendor's own user as the booking owner.
            $table->string('customer_name', 120)->nullable()->after('source');
            $table->string('customer_phone', 30)->nullable()->after('customer_name');
            $table->string('walkin_notes', 500)->nullable()->after('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['source', 'customer_name', 'customer_phone', 'walkin_notes']);
        });
    }
};
