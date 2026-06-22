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
        // Convert existing values first
        DB::table('registrations')->where('status', 'accepted')->update(['status' => 'diterima']);
        DB::table('registrations')->where('status', 'rejected')->update(['status' => 'tidak_diterima']);

        // Change column type to string to support new values (PostgreSQL handles enum differently)
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('registrations')->where('status', 'diterima')->update(['status' => 'accepted']);
        DB::table('registrations')->where('status', 'tidak_diterima')->update(['status' => 'rejected']);

        Schema::table('registrations', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->change();
        });
    }
};
