<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained()->onDelete('cascade');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->boolean('is_telat')->default(false);
            $table->integer('durasi_telat')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absens');
    }
};
