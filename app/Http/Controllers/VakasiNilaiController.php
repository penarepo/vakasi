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
        ], [
            'file.required' => 'File wajib diisi.',
            'file.mimes' => 'Format file harus csv,xls atau xlsx.'
        ]);

        VakasiNilai::whereNotNull('id')->delete();

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('vakasi_nilai', $nama_file);

        Excel::import(new VakasiNilaiImport, public_path('/vakasi_nilai/' . $nama_file));

        Session::flash('sukses', 'Data Nilai Berhasil Diimport!');

        return redirect('/');
    }

    public function getVakasiNilai(Request $request)
    {
        $data = VakasiNilai::select('dosen_pengajar', 'nip', 'nidn','prodi')
            ->groupBy('dosen_pengajar', 'nip', 'nidn','prodi')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // $rows = '<div class="text-center align-middle center"><a href="javascript:;" id="btnDetail" class="btn btn-sm btn-success btn-style rounded" data="' . $row['nip'] . '">Detail</a> <a href="/cetak-vakasi-nilai/' . $row['nip'] . '/' . $row['prodi'] . '" id="btnCetak" target="_blank" class="btn btn-sm btn-primary btn-style rounded">Cetak</a></div>';
                    $rows = '<div class="text-center align-middle center"><a href="javascript:;" id="btnDetail" class="btn btn-sm btn-success btn-style rounded" data="' . $row['nip'] . '">Detail</a> <a href="/cetak-vakasi-nilai/' . $row['nip'] . '" id="btnCetak" target="_blank" class="btn btn-sm btn-primary btn-style rounded">Cetak</a></div>';
                    return $rows;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDataKelas(Request $request)
    {
        // $data = VakasiNilai::select('dosen_pengajar', 'nip', 'nidn','prodi')
        //     ->groupBy('dosen_pengajar', 'nip', 'nidn','prodi')
        //     ->get();
        // $data = VakasiNilai::all();
        $data = VakasiNilai::selectRaw('id, kode_mk, dosen_pengajar,nama_mk, nama_kelas, jumlah_peserta_kelas, tgl_uts, CAST(tgl_pengisian_nilai as date) AS tgl_pengisian_nilai, date_add(tgl_uts ,interval 14 day) as batas_upload, if(tgl_uts <= CAST(tgl_pengisian_nilai as DATE), if(CAST(tgl_pengisian_nilai as DATE) <= date_add(tgl_uts ,interval 14 DAY),"Tepat","Telat"),"Belum Upload") AS status')->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $rows = '<div class="text-center align-middle center"><a href="javascript:;" id="btnDetailKelas" class="btn btn-sm btn-success btn-style rounded" data="' . $row['id'] . '">Ubah</a></div>';
                    return $rows;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDataDetailKelas($id)
    {
            $mk = VakasiNilai::where('id', $id)
            ->first();

            return response()->json($mk);
    }

    // public function mkdetail($id)
    // {
    //     $mk = VakasiNilai::selectRaw('nama_mk, nama_kelas, jumlah_peserta_kelas, tgl_uts, CAST(tgl_pengisian_nilai as date) AS tgl_pengisian_nilai, date_add(tgl_uts ,interval 14 day) as batas_upload, if(tgl_uts <= CAST(tgl_pengisian_nilai as DATE), if(CAST(tgl_pengisian_nilai as DATE) <= date_add(tgl_uts ,interval 14 DAY),"Tepat","Telat"),"Belum Upload") AS status')
    //         ->where('nip', $id)
    //         ->where('nama_mk', '!=', "Magang/KKN")
    //         ->orderBy('nama_mk')
    //         ->get();

    //     return response()->json($mk);
    // }

    public function mkVakasiNilai($id)
    {
        $mk = VakasiNilai::selectRaw('nama_mk, nama_kelas, jumlah_peserta_kelas, tgl_uts, CAST(tgl_pengisian_nilai as date) AS tgl_pengisian_nilai, date_add(tgl_uts ,interval 14 day) as batas_upload, if(tgl_uts <= CAST(tgl_pengisian_nilai as DATE), if(CAST(tgl_pengisian_nilai as DATE) <= date_add(tgl_uts ,interval 14 DAY),"Tepat","Telat"),"Belum Upload") AS status, status_pencairan')
            ->where('nip', $id)
            ->where('nama_mk', '!=', "Magang/KKN")
            ->orderBy('nama_mk')
            ->get();

        return response()->json($mk);
    }

    public function cetakVakasiNilai($id)
    // public function cetakVakasiNilai($id, $prodi)
    {
        $vakasi = VakasiNilai::selectRaw('id, nip, periode, id_kelas, kode_mk, nama_mk, nama_kelas, jumlah_peserta_kelas, tgl_uts, CAST(tgl_pengisian_nilai as date) AS tgl_pengisian_nilai, date_add(tgl_uts ,interval 14 day) as batas_upload, if(tgl_uts <= CAST(tgl_pengisian_nilai as DATE), if(CAST(tgl_pengisian_nilai as DATE) <= date_add(tgl_uts ,interval 14 DAY),"Tepat","Telat"),"Belum Upload") AS status, bonus_tepat_mengajar')
            ->where('nip', $id)
            ->where('nama_mk', '!=', "Magang/KKN")
            ->where('status_pencairan', '!=', "Y")
            ->orderBy('nama_mk')
            ->get();

        // $setting_vakasi = SettingVakasi::where('prodi', 'like', "%" . $prodi . "%")
        $setting_vakasi = SettingVakasi::where('prodi', 'like', "%all%")
            ->first();

        $data_dosen = VakasiNilai::select('dosen_pengajar', 'nip')
            ->groupBy('dosen_pengajar', 'nip')
            ->where('nip', $id)
            ->first();

        $total = [];
        foreach ($vakasi as $item) {
            if ($item['tgl_uts'] <= $item['tgl_pengisian_nilai']) {
                if ($item['tgl_pengisian_nilai'] <= $setting_vakasi['batas_upload']) {
                    $total[] = ($item['jumlah_peserta_kelas'] * $setting_vakasi->honor_soal) + $item['bonus_tepat_mengajar'] + $setting_vakasi['honor_pembuat_soal'];
                } else {
                    $total[] = ($item['jumlah_peserta_kelas'] * $setting_vakasi->honor_soal_lewat) + $item['bonus_tepat_mengajar'] + $setting_vakasi['honor_pembuat_soal'];
                }
                // echo($item['id']);
                $mk = VakasiNilai::find($item['id']);
                // echo($mk);
                $mk->status_pencairan = "Y";
                $mk->save();
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

    public function dataKelas()
    {
        $data = [
            'data_vakasi' => VakasiNilai::paginate(10)
        ];

        return view('data-kelas', $data);
    }

    public function updateKelas(Request $request)
    {
        $post = VakasiNilai::find($request->id);
        $post->tgl_pengisian_nilai    = $request->tgl_pengisian_nilai;
        $post->bonus_tepat_mengajar  = $request->bonus_tepat_mengajar;
        $post->status_pencairan  = $request->status_pencairan;
        $post->save();

        return redirect('data-kelas')->with(['sukses' => 'Data Berhasil Diubah!']);
    }
}