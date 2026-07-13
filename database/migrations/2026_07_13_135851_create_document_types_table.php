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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('max_size_kb')->default(5120);
            $table->string('allowed_extensions')->default('pdf,jpg,jpeg,png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
