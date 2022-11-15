<?php

namespace App\Http\Controllers;

use App\Imports\VakasiNilaiImport;
use App\Models\SettingVakasi;
use App\Models\VakasiNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class VakasiNilaiController extends Controller
{
    //
    public function index()
    {
        $data = [
            'data_vakasi' => VakasiNilai::paginate(10)
        ];

        return view('vakasi-nilai', $data);
    }

    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        VakasiNilai::whereNotNull('id')->delete();

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('vakasi_nilai', $nama_file);

        Excel::import(new VakasiNilaiImport, public_path('/vakasi_nilai/' . $nama_file));

        Session::flash('sukses', 'Data Siswa Berhasil Diimport!');

        return redirect('/');
    }

    public function getVakasiNilai(Request $request)
    {
        $data = VakasiNilai::select('dosen_pengajar', 'nip')
            ->groupBy('dosen_pengajar', 'nip')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $rows = '<div class="text-center align-middle center"><a href="javascript:;" id="btnDetail" class="btn btn-sm btn-success btn-style rounded" data="' . $row['nip'] . '">Detail</a> <a href="/cetak-vakasi-nilai/' . $row['nip'] . '" id="btnDetail" class="btn btn-sm btn-primary btn-style rounded">Cetak</a></div>';
                    return $rows;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function mkVakasiNilai($id)
    {
        $mk = VakasiNilai::selectRaw('nama_mk, nama_kelas, jumlah_peserta_kelas, tgl_uts, CAST(tgl_pengisian_nilai as date) AS tgl_pengisian_nilai, date_add(tgl_uts ,interval 14 day) as batas_upload, if(tgl_uts <= CAST(tgl_pengisian_nilai as DATE), if(CAST(tgl_pengisian_nilai as DATE) <= date_add(tgl_uts ,interval 14 DAY),"Tepat","Telat"),"Belum Upload") AS status')
            ->where('nip', $id)
            ->where('nama_mk', '!=', "Magang/KKN")
            ->orderBy('nama_mk')
            ->get();

        return response()->json($mk);
    }

    public function cetakVakasiNilai($id)
    {
        $vakasi = VakasiNilai::selectRaw('nama_mk, nama_kelas, jumlah_peserta_kelas, tgl_uts, CAST(tgl_pengisian_nilai as date) AS tgl_pengisian_nilai, date_add(tgl_uts ,interval 14 day) as batas_upload, if(tgl_uts <= CAST(tgl_pengisian_nilai as DATE), if(CAST(tgl_pengisian_nilai as DATE) <= date_add(tgl_uts ,interval 14 DAY),"Tepat","Telat"),"Belum Upload") AS status')
            ->where('nip', $id)
            ->where('nama_mk', '!=', "Magang/KKN")
            ->orderBy('nama_mk')
            ->get();

        $setting_vakasi = SettingVakasi::where('prodi', 'like', "%" . "Ilmu Hukum" . "%")
            ->first();

        $data_dosen = VakasiNilai::select('dosen_pengajar', 'nip')
            ->groupBy('dosen_pengajar', 'nip')
            ->where('nip', $id)
            ->first();

        foreach ($vakasi as $item) {
            if ($item->tgl_uts <= $item->tgl_pengisian_nilai) {
                if ($item->tgl_pengisian_nilai <= $item->batas_upload) {
                    $total[] = ($item->jumlah_peserta_kelas * $setting_vakasi->bonus) + $setting_vakasi->honor_soal + $setting_vakasi->honor_pengawas;
                } else {
                    $total[] = ($item->jumlah_peserta_kelas * $setting_vakasi->bonus_lewat) + $setting_vakasi->honor_soal + $setting_vakasi->honor_pengawas;
                }
            } else {
                $total[] = 0;
            }
        }

        $data = [
            'vakasi' => $vakasi,
            'setting' => $setting_vakasi,
            'total' => array_sum($total),
            'data_dosen' => $data_dosen
        ];

        $pdf = PDF::loadview('laporan-vakasi-pdf', $data);
        return $pdf->stream('laporan-vakasi-pdf');
    }
}
