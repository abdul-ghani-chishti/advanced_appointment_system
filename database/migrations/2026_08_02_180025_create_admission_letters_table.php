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
        Schema::create('admission_letters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('student_name')->nullable();

            $table->string('university_name')->nullable();

            $table->string('program_name')->nullable();

            $table->date('admission_date')->nullable();

            $table->string('semester')->nullable();

            $table->unsignedTinyInteger('parser_version')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_letters');
    }
};
