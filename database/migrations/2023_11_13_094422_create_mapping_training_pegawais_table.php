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
        Schema::create('mapping_training_pegawais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id')->unsigned();
            $table->unsignedBigInteger('training_id')->unsigned();

            $table->foreign('pegawai_id')->references('id')->on('pegawais')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('training_id')->references('id')->on('trainings')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapping_training_pegawais');
    }
};
