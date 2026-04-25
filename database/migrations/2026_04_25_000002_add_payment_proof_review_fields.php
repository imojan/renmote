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
            $table->text('proof_review_notes')
                ->nullable()
                ->after('proof_notes');
            $table->dateTime('proof_reviewed_at')
                ->nullable()
                ->after('proof_uploaded_at');
            $table->enum('proof_reviewer_role', ['vendor', 'admin'])
                ->nullable()
                ->after('proof_reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'proof_review_notes',
                'proof_reviewed_at',
                'proof_reviewer_role',
            ]);
        });
    }
};
