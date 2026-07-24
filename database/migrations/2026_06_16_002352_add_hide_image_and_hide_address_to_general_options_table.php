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
        Schema::table('general_options', function (Blueprint $table) {
            $table->boolean('hide_image')->default(false)->after('hide_email');
            $table->boolean('hide_address')->default(false)->after('hide_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_options', function (Blueprint $table) {
            $table->dropColumn(['hide_image', 'hide_address']);
        });
    }
};
