<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <style>
        body {
            font-family: 'Arial';
            font-size: 12px;
        }

    </style>
    <title>Cetak Vakasi Ujian</title>
</head>

<body>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <h4 class="text-center">BUKTI PENGAMBILAN VAKASI SOAL DAN KOREKSI NILAI</h4>
    <h4 class="text-center">UJIAN TENGAH SEMESTER</h4>
    <br>
    <br>
    <table class="table table-sm table-borderless">
        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>{{ date("d-m-Y") }}</td>
            <td><b>Semester</b></td>
            <td>:</td>
            <td>{{ $setting->semester }}</td>
        </tr>
        <tr>
            <td><b>Vakasi Soal</b></td>
            <td>:</td>
            <td>Rp {{ number_format($setting->honor_soal,0,',','.') }}</td>
            <td><b>Program Studi</b></td>
            <td>:</td>
            <td>{{ $setting->prodi }}</td>
        </tr>
        <tr>
            <td><b>Transport Pengawas</b></td>
            <td>:</td>
            <td>Rp {{ number_format($setting->honor_pengawas,0,',','.') }}</td>
            <td><b>Program</b></td>
            <td>:</td>
            <td>{{ $setting->program }}</td>
        </tr>
        <tr>
            <td><b>Jumlah</b></td>
            <td>:</td>
            <td>Rp {{ number_format($setting->honor_soal + $setting->honor_pengawas ,0,',','.') }}</td>
            <td><b>Nama Dosen</b></td>
            <td>:</td>
            <td>{{ $data_dosen->dosen_pengajar }}</td>
        </tr>
    </table>

    <table class="table table-striped table-sm mt-4">
        <thead>
            <tr class="table-primary text-center">
                <th>Nama Mata Kuliah</th>
                <th>Jumlah Mhs</th>
                <th>Tgl Ujian</th>
                <th>Batas Upload</th>
                <th>Tgl Upload</th>
                <th>Status</th>
                <th>Honor</th>
                <th>Jumlah</th>
                <th>Jumlah Total</th>
            </tr>
        </thead>
        <tbody>
            @if (count($vakasi) != 0)
            @foreach ($vakasi as $item)
            <tr class="text-center">
                <td>{{ $item['nama_mk'] }} - {{ $item['nama_kelas'] }}</td>
                <td>{{ $item['jumlah_peserta_kelas'] }}</td>
                <td>{{ date('d-m-Y', strtotime($item['tgl_uts'])) }}</td>
                <td>{{ date('d-m-Y', strtotime($item['tgl_uts'] . ' + 14 days')) }}</td>
                <td>{{ date('d-m-Y', strtotime($item['tgl_pengisian_nilai'])) }}</td>

                @if ($item['tgl_uts'] <= $item['tgl_pengisian_nilai'])
                    @if ($item['tgl_pengisian_nilai'] <= $item['batas_upload'])
                            <td>Tepat</td>
                            <td>Rp {{ number_format($setting->bonus,0,',','.') }}</td>
                            <td>Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->bonus,0,',','.') }}</td>
                            <td>Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->bonus + $setting->honor_soal + $setting->honor_pengawas ,0,',','.') }}</td>
                        @else
                            <td>Terlambat</td>
                            <td>Rp {{ number_format($setting->bonus_lewat,0,',','.') }}</td>
                            <td>Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->bonus_lewat,0,',','.') }}</td>
                            <td>Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->bonus_lewat + $setting->honor_soal + $setting->honor_pengawas ,0,',','.') }}</td>
                        @endif
                @else
                    <td>Belum Upload</td>
                    <td>Rp 0</td>
                    <td>Rp 0</td>
                    <td>Rp 0</td>
                @endif
            </tr>
            @endforeach
            <tr class="text-center">
                <td colspan="8"><b>Total</b></td>
                <td><b>Rp {{ number_format($total,0,',','.') }}</b></td>
            </tr>
                  
            @else
            <tr class="text-center">
                <td colspan="9"><b>Belum Upload Nilai</b></td>
            </tr> 
            @endif
        </tbody>
    </table>
    <br>
    <div style="right: -320; position: relative;">
        <p><b>Diterima Oleh,</b></p>
        <br>
        <br>
        <br>
        <p><b><u>{{ $data_dosen->dosen_pengajar }}</u></b></p>
    </div>
</body>

</html>
