<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('book_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('chapter');          // from request form
            $table->string('purpose');
            $table->string('note')->nullable();
            $table->date('needed_by');

            $table->enum('status', ['pending', 'processing', 'completed'])
                  ->default('pending');

            // For completed requests
            $table->string('completed_file')->nullable(); // file path or URL
            $table->string('prepared_by')->nullable();     // admin name
            $table->timestamp('expiration_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_requests');
    }
};
