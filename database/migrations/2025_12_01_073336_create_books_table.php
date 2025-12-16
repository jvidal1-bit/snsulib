<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('year_published')->nullable();
            $table->string('isbn')->nullable()->unique()->nullable();
            $table->unsignedInteger('total_pages')->nullable();

            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->enum('status', ['available', 'unavailable'])
                  ->default('available');

            $table->text('description')->nullable();
            $table->longText('table_of_contents')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
