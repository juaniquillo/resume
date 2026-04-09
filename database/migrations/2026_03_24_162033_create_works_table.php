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
        Schema::create('works', function (Blueprint $table) {
            $table->id();

            $table->uuid('uid');

            $table->string('name');
            $table->string('position');

            $table->text('summary')->nullable();

            $table
                ->foreignUlid('user_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
