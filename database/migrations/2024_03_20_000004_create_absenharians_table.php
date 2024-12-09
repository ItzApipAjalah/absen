<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absenharians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('total_check_in')->default(0);
            $table->integer('total_time')->default(0);
            $table->integer('total_late')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absenharians');
    }
};
