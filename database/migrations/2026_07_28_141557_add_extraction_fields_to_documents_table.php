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
        Schema::table('documents', function (Blueprint $table) {
            $table->longText('extracted_text')
                ->nullable()
                ->after('stored_name');

            $table->unsignedInteger('extracted_character_count')
                ->nullable()
                ->after('stored_name');

            $table->timestamp('processed_at')
                ->nullable()
                ->after('extracted_character_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'extracted_text',
                'extracted_character_count',
            ]);
        });
    }
};
