<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->integer('number')->default(0);
            $table->string('suffix', 10)->nullable()->default('+');
            $table->string('icon', 50)->default('fa-star');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default 4 counters requested by user
        DB::table('counters')->insert([
            [
                'label'      => 'Enrolled Students',
                'number'     => 1000,
                'suffix'     => '+',
                'icon'       => 'fa-user-graduate',
                'sort_order' => 1,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label'      => 'Programs Offered',
                'number'     => 50,
                'suffix'     => '+',
                'icon'       => 'fa-book-open',
                'sort_order' => 2,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label'      => 'Schools',
                'number'     => 5,
                'suffix'     => null,
                'icon'       => 'fa-university',
                'sort_order' => 3,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label'      => 'Graduated Students',
                'number'     => 500,
                'suffix'     => '+',
                'icon'       => 'fa-award',
                'sort_order' => 4,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counters');
    }
};
