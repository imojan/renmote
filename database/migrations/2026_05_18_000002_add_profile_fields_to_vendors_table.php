<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Profile photo (avatar bisnis) — ditampilkan di topbar saat vendor login.
            $table->string('profile_photo')->nullable()->after('bank_account');
            // Cover photo / foto background toko — dipakai sebagai header kartu vendor.
            $table->string('cover_photo')->nullable()->after('profile_photo');
            // Rating manual yang diinput vendor (sumber: review Maps di luar platform).
            $table->decimal('rating', 3, 2)->nullable()->after('cover_photo');
            $table->unsignedInteger('rating_count')->nullable()->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'cover_photo', 'rating', 'rating_count']);
        });
    }
};
