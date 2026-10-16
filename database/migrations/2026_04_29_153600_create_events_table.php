<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->enum('category', ['Latest News', 'Upcoming Event', 'Past Event', 'Job Vacancy'])->default('Latest News');
            $table->string('author')->default('Admin');
            $table->string('image')->nullable();
            $table->text('short_description');
            $table->longText('full_description');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
