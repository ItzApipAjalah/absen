<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasis')->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('late_tolerance');
            $table->enum('status', ['active', 'inactive']);
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwals');
    }
};
