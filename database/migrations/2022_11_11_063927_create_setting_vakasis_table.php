<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingVakasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_vakasis', function (Blueprint $table) {
            $table->id();
            $table->string('prodi')->nullable();
            $table->string('program')->nullable();
            $table->string('semester')->nullable();
            $table->integer('honor_soal')->nullable();
            $table->integer('honor_soal_lewat')->nullable();
            $table->integer('honor_pengawas')->nullable();
            $table->integer('honor_pembuat_soal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_vakasis');
    }
}
