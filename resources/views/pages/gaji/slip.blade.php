<!DOCTYPE html>
<html lang="en">
<head>
    @php
        use Carbon\Carbon;
        use App\AbsenModel;
        use App\GajiModel;
        use App\ShiftModel;
        use App\KaryawanModel;

    @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Slip Gaji</title>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .page {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .slip {
            width: 45%;
            padding: 20px;
            margin-bottom: 20px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .slip h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .company-name {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .salary-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .salary-details th, .salary-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .salary-details th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .salary-details tr.total, .salary-details tr.grand-total {
            background-color: #f2f2f2;
        }

        .salary-details td:last-child {
            text-align: right;
        }

        .salary-details .total td, .salary-details .grand-total td {
            font-weight: bold;
        }

        .salary-details tr.total td, .salary-details tr.grand-total td {
            text-align: right;
        }

</style>
</head>
<body>
<div class="page">
    @php
        $data = KaryawanModel::all();
    @endphp
        @foreach ($data as $item)
            @php
                $id = $item->id;
                $idd = $item->id_absen;
                $absen = AbsenModel::where('id_pegawai', $idd)
                        ->whereYear('tanggal', '=', Carbon::parse($mo)->year)
                        ->whereMonth('tanggal', '=', Carbon::parse($mo)->month)
                        ->get();
                $terlambat = $absen->where('keterangan', 'tidak_tepat_waktu')->count();
                $jumlah = $absen->where('id_pegawai', $idd)->count();
                $nshift = ShiftModel::where('id', $item->id_shift)->first();
                $sm = $nshift->jam_masuk;
                $sp = $nshift->jam_pulang;
                $start_time = Carbon::createFromFormat('H:i', $sm);
                $end_time = Carbon::createFromFormat('H:i', $sp);
                // gaji kotor
                $gaji = GajiModel::where('id_pegawai', $id)->first();
                $gaji_pokok = GajiModel::where('id_pegawai', $id)->where('status','gaji_pokok')->value('jumlah');
                $mnt = $end_time ? $end_time->diffInMinutes($start_time) : null;
                $salary_menit = $gaji_pokok / $mnt / 60;
                // tunjangan
                $tunjangan = GajiModel::where('id_pegawai', $id)
                                ->whereNotIn('status', ['gaji_pokok']) // Mengabaikan baris dengan status 'gaji_pokok'
                                ->sum('jumlah');
                // potongan terlambat
                $potongan = PotonganModel::where('id_pegawai',$item->id)
                                                ->where('status','terlambat')
                                                ->value('jumlah');
                $potongan_terlambat = $terlambat * $potongan /100;
                // gaji pokok
                $gaji_pokok = $gaji_pokok * $jumlah;
                // uang bensin 
                $uang_bensin = GajiModel::where('id_pegawai', $id)
                                ->where('status', 'uang_bensin') // Mengabaikan baris dengan status 'gaji_pokok'
                                ->value('jumlah');
                //uang makan 
                $uang_makan = GajiModel::where('id_pegawai', $id)
                                ->where('status', 'uang_makan') // Mengabaikan baris dengan status 'gaji_pokok'
                                ->value('jumlah');
            @endphp
            <div class="slip">
                <div class="company-name">Cinta Bunda</div>
                <h2>Slip Gaji</h2>
                <div class="info">
                    <div class="info-item">
                        <span>Nama:</span>
                        <span>{{$item->name}}</span>
                    </div>
                    <div class="info-item">
                        <span>Jabatan:</span>
                        <span>{{$item->jabatan}}</span>
                    </div>
                    <div class="info-item">
                        <span>Tanggal:</span>
                        <span>{{date('Y-M-d')}}</span>
                    </div>
                </div>
                <table class="salary-details">
                    <table class="salary-details">
                        <tr>
                            <th>Pendapatan</th>
                            <th>jumlah</th>
                            <th>Potongan</th>
                            <th>jumlah</th>
                        </tr>
                        <tr>
                            <td>Gaji Pokok</td>
                            <td>{{$gaji_pokok}}</td>
                            <td>Terlambat</td>
                            <td>{{$potongan_terlambat}}</td>
                        </tr>
                        <tr>
                            <td>Uang Bensin</td>
                            <td>{{$uang_bensin}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Uang Makan</td>
                            <td>{{$uang_makan}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="total">
                            <td>Total Pendapatan</td>
                            <td colspan="2">Rp 13.000.000</td>
                        </tr>
                        <tr>
                            <td>Potongan Pajak</td>
                            <td></td>
                            <td>Rp 1.500.000</td>
                        </tr>
                        <tr>
                            <td>Potongan Lain-lain</td>
                            <td></td>
                            <td>Rp 500.000</td>
                        </tr>
                        <tr class="total">
                            <td>Total Potongan</td>
                            <td colspan="2">Rp 2.000.000</td>
                        </tr>
                        <tr class="grand-total">
                            <td>Take Home Pay</td>
                            <td colspan="2">Rp 11.000.000</td>
                        </tr>
                        </table>
                </table>
            </div>
        @endforeach
    
</div>
</body>
</html>
