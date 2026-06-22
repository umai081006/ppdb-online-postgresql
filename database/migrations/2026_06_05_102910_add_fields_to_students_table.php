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
        Schema::table('students', function (Blueprint $table) {
            $table->string('nik', 16)->unique()->nullable()->after('user_id');
            $table->string('agama')->nullable()->after('jenis_kelamin');
            $table->string('pekerjaan_orang_tua')->nullable()->after('nama_ibu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['nik', 'agama', 'pekerjaan_orang_tua']);
        });
    }
};
