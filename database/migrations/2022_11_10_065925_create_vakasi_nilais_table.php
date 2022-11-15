<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVakasiNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vakasi_nilais', function (Blueprint $table) {
            $table->id();
            $table->string('periode')->nullable();
            $table->string('id_kelas')->nullable();
            $table->string('kode_mk')->nullable();
            $table->string('nama_mk')->nullable();
            $table->string('nama_kelas')->nullable();
            $table->dateTime('tgl_pengisian_nilai')->nullable();
            $table->string('status_finalisasi_nilai')->nullable();
            $table->date('tgl_finalisasi_nilai')->nullable();
            $table->integer('jumlah_peserta_kelas')->nullable();
            $table->date('tgl_uts')->nullable();
            $table->dateTime('waktu_mulai_uts')->nullable();
            $table->dateTime('waktu_selesai_uts')->nullable();
            $table->date('tgl_uas')->nullable();
            $table->dateTime('waktu_mulai_uas')->nullable();
            $table->dateTime('waktu_selesai_uas')->nullable();
            $table->string('nip')->nullable();
            $table->string('dosen_pengajar')->nullable();
            $table->string('nidn')->nullable();
            $table->string('prodi')->nullable();
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
        Schema::dropIfExists('vakasi_nilais');
    }
}
