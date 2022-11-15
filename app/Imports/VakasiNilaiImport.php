<?php

namespace App\Imports;

use App\Models\VakasiNilai;
use Maatwebsite\Excel\Concerns\ToModel;

class VakasiNilaiImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new VakasiNilai([
            'periode' => $row[1],
            'id_kelas' => $row[2],
            'kode_mk' => $row[3],
            'nama_mk' => $row[4],
            'nama_kelas' => $row[5],
            'tgl_pengisian_nilai' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['6']),
            'status_finalisasi_nilai' => $row[7],
            'tgl_finalisasi_nilai' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['8']),
            'jumlah_peserta_kelas' => $row[9],
            'tgl_uts' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10]),
            'waktu_mulai_uts' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[11]),
            'waktu_selesai_uts' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[12]),
            'tgl_uas' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[13]),
            'waktu_mulai_uas' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[14]),
            'waktu_selesai_uas' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[15]),
            'nip' => $row[16],
            'dosen_pengajar' => $row[17],
            'nidn' => $row[18],
            'prodi' => $row[19],
        ]);
    }
}
