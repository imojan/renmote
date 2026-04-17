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
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'media_path')) {
                $table->string('media_path')->nullable()->after('body');
            }

            if (!Schema::hasColumn('messages', 'media_type')) {
                $table->string('media_type', 20)->nullable()->after('media_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'media_type')) {
                $table->dropColumn('media_type');
            }

            if (Schema::hasColumn('messages', 'media_path')) {
                $table->dropColumn('media_path');
            }
        });
    }
};
