<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Library info
            $table->string('library_name')->nullable();
            $table->string('operation_hours')->nullable();
            $table->string('library_contact')->nullable();
            $table->string('library_email')->nullable();
            $table->text('library_address')->nullable();

            // Request settings
            $table->unsignedInteger('max_chapters_per_request')->default(1);
            $table->unsignedInteger('request_expiry_days')->default(7);
            $table->boolean('notify_on_request')->default(true);
            $table->boolean('notify_on_complete')->default(true);
            $table->boolean('notify_on_expiry')->default(false);

            // Extra: footer text (for your footer logos / site footer)
            $table->string('footer_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
