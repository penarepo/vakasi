<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VakasiNilai extends Model
{
    use HasFactory;
    protected $table = "vakasi_nilais";
    protected $fillable = [
        'periode',
        'id_kelas',
        'kode_mk',
        'nama_mk',
        'nama_kelas',
        'tgl_pengisian_nilai',
        'status_finalisasi_nilai',
        'tgl_finalisasi_nilai',
        'jumlah_peserta_kelas',
        'tgl_uts',
        'waktu_mulai_uts',
        'waktu_selesai_uts',
        'tgl_uas',
        'waktu_mulai_uas',
        'waktu_selesai_uas',
        'nip',
        'dosen_pengajar',
        'nidn',
        'prodi',
    ];
}
