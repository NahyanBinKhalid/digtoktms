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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 3);
            $table->string('key', 255);
            $table->text('content');
            $table->jsonb('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('translations', function (Blueprint $table) {
            $table->unique(['locale', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
