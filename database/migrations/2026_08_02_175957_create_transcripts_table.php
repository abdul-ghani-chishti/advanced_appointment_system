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
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('student_name')->nullable();

            $table->string('university_name')->nullable();

            $table->string('degree_name')->nullable();

            $table->decimal('cgpa',4,2)->nullable();

            $table->unsignedSmallInteger('total_credits')->nullable();

            $table->date('graduation_date')->nullable();

            $table->unsignedTinyInteger('parser_version')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
