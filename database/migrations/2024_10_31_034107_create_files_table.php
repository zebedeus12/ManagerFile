<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql_second')->create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('size'); // Ukuran file
            $table->string('type'); // Jenis file (contoh: pdf, jpg)
            $table->foreignId('folder_id')->constrained('folders')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_second')->dropIfExists('files');
    }
};
