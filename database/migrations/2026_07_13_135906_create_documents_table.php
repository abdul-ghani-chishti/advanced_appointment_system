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
        Schema::create('documents', function (Blueprint $table) {
            $table->id()->index();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('document_type_id')->constrained()->restrictOnDelete();

            $table->string('original_name');
            $table->string('stored_name');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size_bytes');

            $table->integer('document_status_id')->default(1)->index();
            $table->text('failure_reason')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'document_type_id'], 'documents_user_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
