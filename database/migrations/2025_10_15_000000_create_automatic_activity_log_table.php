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
        Schema::create('automatic_activity_log', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('causer');
            $table->string('action_type')->comment('e.g., created, updated, deleted');
            $table->longText('description');
            $table->nullableMorphs('affected_model');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automatic_activity_log');
    }
};
