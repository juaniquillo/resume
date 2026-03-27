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
        Schema::create('basics', function (Blueprint $table) {
            
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('label');
            $table->string('image')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('url')->nullable();
            $table->string('summary')->nullable();

            $table->json('location')->nullable();
            $table->json('profiles')->nullable();

            $table
                ->foreignUlid('user_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basics');
    }
};
