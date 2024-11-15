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
        Schema::connection('mysql_second')->create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('accessibility', ['public', 'private']);
            $table->foreignId('parent_id')->nullable()->constrained('folders');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_second')->dropIfExists('folders');
    }
};
