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
        Schema::create('passports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('passport_number')->nullable();

            $table->string('surname')->nullable();
            $table->string('given_names')->nullable();
            $table->string('full_name')->nullable();

            $table->string('nationality')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('sex')->nullable();

            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();

            $table->string('issuing_authority')->nullable();
            $table->string('mrz_line_1')->nullable();
            $table->string('mrz_line_2')->nullable();

            $table->unsignedTinyInteger('parser_version')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
