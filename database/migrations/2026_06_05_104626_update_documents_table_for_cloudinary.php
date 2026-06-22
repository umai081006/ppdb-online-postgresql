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
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('file_path');
            $table->string('cloudinary_url')->nullable()->after('tipe_dokumen');
            $table->string('cloudinary_public_id')->nullable()->after('cloudinary_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['cloudinary_url', 'cloudinary_public_id']);
            $table->string('file_path')->nullable();
        });
    }
};
