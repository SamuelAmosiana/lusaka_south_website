<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Site logo, uploaded from Admin > Home Page > Hero Banner (stored on the home-hero row)
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
