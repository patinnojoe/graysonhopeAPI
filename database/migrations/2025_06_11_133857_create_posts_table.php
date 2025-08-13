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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('category')->nullable();
            $table->string('date')->nullable();
            $table->text('content')->nullable();
            $table->json('images')->nullable();
            $table->json('extra_details')->nullable();
            $table->string('author')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
