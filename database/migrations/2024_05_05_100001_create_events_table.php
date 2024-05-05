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
        Schema::disableForeignKeyConstraints();

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->index('user_id');
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('name', 255);
            $table->text('description');
            $table->string('location', 255);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('capacity');
            $table->integer('current_attendees')->default(0);
            $table->string('category', 100);
            $table->string('picture', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('finish_in')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
