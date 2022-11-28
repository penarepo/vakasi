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
    <h4 class="text-center">BUKTI PENGAMBILAN VAKASI SOAL</h4>
    <h4 class="text-center">KOREKSI NILAI DAN INSENTIF</h4>
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
            <td><b>Nama Dosen</b></td>
            <td>:</td>
            <td>{{ $data_dosen->dosen_pengajar }}</td>
            <td><b>Program</b></td>
            <td>:</td>
            <td>{{ $setting->program }}</td>
        </tr>
    </table>

    <table class="table table-striped table-sm mt-4">
        <thead>
            <tr class="table-primary text-center">
                <th rowspan="2">Kode MK</th>
                <th rowspan="2">Nama Mata Kuliah</th>
                <th rowspan="2">Kelas</th>
                <th rowspan="2">Jumlah Mhs</th>
                <th rowspan="2">Status</th>
                <th colspan="2">Honor</th>
                <th rowspan="2" class="text-right">Jumlah Total</th>
            </tr>
            <tr class="table-primary text-center">
                <!-- <th>Pembuatan Soal</th> -->
                <th>Tepat Mengajar</th>
                <th>Periksa Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalakhir = 0
            @endphp
            @if (count($vakasi) != 0)
            @foreach ($vakasi as $item)
            <tr class="text-center">

                @if ($item['tgl_uts'] <= $item['tgl_pengisian_nilai'] && $item['cetak'] <= 1)
                    @if ($item['tgl_pengisian_nilai'] <= $item['batas_upload'])
                        <td>{{ $item['kode_mk'] }}</td>
                        <td>{{ $item['nama_mk'] }}</td>
                        <td>{{ $item['nama_kelas'] }}</td>
                        <td>{{ $item['jumlah_peserta_kelas'] }}</td>
                        <td>Tepat</td>
                        <!-- <td>Rp {{ number_format($setting['honor_pembuat_soal'],0,',','.') }}</td> -->
                        <td>Rp {{ number_format($item['bonus_tepat_mengajar'],0,',','.') }}</td>
                        <td>Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->honor_soal,0,',','.') }}</td>
                        <td class="text-right">Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->honor_soal + $item['bonus_tepat_mengajar'] + $setting['honor_pembuat_soal'],0,',','.') }}</td>
                        @php
                        $totalakhir += ($item['jumlah_peserta_kelas'] * $setting->honor_soal) + $item['bonus_tepat_mengajar'] + $setting['honor_pembuat_soal']
                        @endphp
                    @else
                        <td>{{ $item['kode_mk'] }}</td>
                        <td>{{ $item['nama_mk'] }}</td>
                        <td>{{ $item['nama_kelas'] }}</td>
                        <td>{{ $item['jumlah_peserta_kelas'] }}</td>
                        <td>Terlambat</td>
                        <!-- <td>Rp {{ number_format($setting['honor_pembuat_soal'],0,',','.') }}</td> -->
                        <td>Rp {{ number_format($item['bonus_tepat_mengajar'],0,',','.') }}</td>
                        <td>Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->honor_soal_lewat,0,',','.') }}</td>
                        <td class="text-right">Rp {{ number_format($item['jumlah_peserta_kelas'] * $setting->honor_soal_lewat + $item['bonus_tepat_mengajar'] + $setting['honor_pembuat_soal'] ,0,',','.') }}</td>
                        @php
                        $totalakhir += ($item['jumlah_peserta_kelas'] * $setting->honor_soal) + $item['bonus_tepat_mengajar'] + $setting['honor_pembuat_soal']
                        @endphp
                    @endif
                @else
                    <td>{{ $item['kode_mk'] }}</td>
                    <td>{{ $item['nama_mk'] }}</td>
                    <td>{{ $item['nama_kelas'] }}</td>
                    <td>{{ $item['jumlah_peserta_kelas'] }}</td>
                    <td>Belum Upload</td>
                    <!-- <td>Rp 0</td> -->
                    <td>Rp 0</td>
                    <td>Rp 0</td>
                    <td>Rp 0</td>
                @endif
            </tr>
            @endforeach

            <tr class="text-center">
                <td colspan="7"><b>Total Insetif dan Vakasi</b></td>
                <td class="text-right"><b>Rp {{ number_format($totalakhir,0,',','.') }}</b></td>
            </tr>
                  
            @else
            <tr class="text-center">
                @php
                    $totalakhir = 0
                @endphp
                <td colspan="8"><b>Belum Upload Nilai</b></td>
            </tr> 
            @endif
        </tbody>
    </table>
    <br>
    @if ($totalakhir != 0)
    <span><b>Honor Pembuatan Soal</b></span>
    <table class="table table-striped table-sm mt-4">
        <thead>
            <tr class="table-primary text-center">
                <th>Kode MK</th>
                <th colspan="6">Nama Mata Kuliah</th>
                <th class="text-right">Jumlah Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalbonus = 0
            @endphp
            @php
            $temp_item = 0
            @endphp

            @if (count($vakasi) != 0)
            @foreach ($vakasi as $item)

                @if (($item['kode_mk'] != $temp_item || $temp_item == "0") && $item['status_bonus_soal'] <= 1)
                    <tr class="text-center">
                        <td>{{ $item['kode_mk'] }}</td>
                        <td colspan="6">{{ $item['nama_mk'] }}</td>
                        <td class="text-right">{{ $setting['honor_pembuat_soal'] }}</td>
                    </tr>
                    @php
                        $totalbonus += $setting['honor_pembuat_soal']
                    @endphp
                @else
                    <!-- <tr>
                        <td>Harus tidak nya muncul</td>
                    </tr> -->
                @endif
                @php
                    $temp_item = $item['kode_mk']
                @endphp
            @endforeach

            <tr class="text-center">
                <td></td>
                <td colspan="6"><b>Total Insentif Pembuatan Soal</b></td>
                <td class="text-right"><b>Rp {{ number_format($totalbonus,0,',','.') }}</b></td>
            </tr>
            <tr class="text-center">
                <td colspan="7"><b>Total Keseluruhan</b></td>
                @php
                    $totalkeseluruhan = $totalakhir + $totalbonus
                @endphp
                <td class="text-right"><b>Rp {{ number_format($totalkeseluruhan,0,',','.') }}</b></td>
            </tr>
            <br>
              
            @else
            <tr class="text-center">
                <td colspan="8"><b>Belum Upload Nilai</b></td>
            </tr> 
            @endif
        </tbody>
    </table>
    @endif
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
