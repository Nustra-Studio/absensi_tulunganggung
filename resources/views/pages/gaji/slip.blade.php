<!DOCTYPE html>
<html lang="en">
<head>
    @php
        use Carbon\Carbon;
        use App\AbsenModel;
        use App\GajiModel;
        use App\ShiftModel;
        use App\KaryawanModel;
        use App\PotonganModel;

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
        if(empty($idd)){
            $data = KaryawanModel::all();
        }
        else {
            $data = KaryawanModel::where('id_absen', $idd)->get();
        }
    @endphp
        @foreach ($data as $item)
            @php
                $id = $item->id;
                $idd = $item->id_absen;
                $absen = AbsenModel::where('id_pegawai', $idd)
                        ->whereYear('tanggal', '=', Carbon::parse($mo)->year)
                        ->whereMonth('tanggal', '=', Carbon::parse($mo)->month)
                        ->get();
                $terlambat = $absen->where('status', 'tidak_tepat_waktu')->count();
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
                $potongan = PotonganModel::where('id_pegawai',$id)
                                                ->where('status','terlambat')
                                                ->value('jumlah');
                $potongan_terlambat =$gaji_pokok * $terlambat * $potongan /100;
                // gaji pokok
                $gaji_pokok = $gaji_pokok * $jumlah;
                // uang bensin 
                $uang_bensin = GajiModel::where('id_pegawai', $id)
                                ->where('status', 'uang_bensin') // Mengabaikan baris dengan status 'gaji_pokok'
                                ->value('jumlah');
                $uang_bensin = $uang_bensin * $jumlah;
                //uang makan 
                $uang_makan = GajiModel::where('id_pegawai', $id)
                                ->where('status', 'uang_makan') // Mengabaikan baris dengan status 'gaji_pokok'
                                ->value('jumlah');
                $uang_makan = $uang_makan * $jumlah;
                // lembur
                $offer_time = 0;
                foreach ($absen as $key => $value) {
                    $end_time = $sp ? Carbon::createFromFormat('H:i', $sp) : null;
                    $finsh_time =$value->absen_pulang ? Carbon::createFromFormat('H:i', $value->absen_pulang) : null;
                    $total_time = $end_time ? $end_time->diffInMinutes($finsh_time ) : null;
                    if ($value->keterangan === "lembur_approve") {
                            $lembur = $salary_menit * $total_time;
                            $offer_time += $lembur;
                        }
                }
                // potongan tambahan
                $potongan_tambahan = PotonganModel::where('id_pegawai',$id)
                                                ->where('status','tambahan')
                                                ->where('keterangan',$mo)
                                                ->value('jumlah');
                // bonus
                $bonus = GajiModel::where('id_pegawai', $id)
                                                ->where('status','bonus')
                                                ->where('keterangan',$mo)
                                                ->value('jumlah');
                // date
                $date = Carbon::createFromFormat('Y-m', $mo);
                $date = $date->format('Y-F');
                 // pendapatan
                    $total = $bonus + $offer_time + $uang_bensin + $uang_makan + $gaji_pokok - $potongan_terlambat -  $potongan_tambahan;

            @endphp
            <div class="slip">
                <div class="company-name">Cinta Bunda</div>
                <h2>Slip Gaji</h2>
                <div class="info">
                    <div class="info-item">
                        <span>Nama:</span>
                        <span>{{$item->nama}}</span>
                    </div>
                    <div class="info-item">
                        <span>Jabatan:</span>
                        <span>{{$item->jabatan}}</span>
                    </div>
                    <div class="info-item">
                        <span>Tanggal:</span>
                        <span>{{$date}}</span>
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
                            <td>{{"Rp " . number_format($gaji_pokok, 0, ',', '.')}}</td>
                            <td>Terlambat</td>
                            <td>{{"Rp " . number_format($potongan_terlambat, 0, ',', '.')}}</td>
                        </tr>
                        <tr>
                            <td>Uang Bensin</td>
                            <td>{{"Rp " . number_format($uang_bensin, 0, ',', '.')}}</td>
                            <td>Lain-Lain</td>
                            <td>{{"Rp " . number_format($potongan_tambahan, 0, ',', '.')}}</td>
                        </tr>
                        <tr>
                            <td>Uang Makan</td>
                            <td>{{"Rp " . number_format($uang_makan, 0, ',', '.')}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Lembur</td>
                            <td>{{"Rp " . number_format($offer_time, 0, ',', '.')}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Bonus</td>
                            <td>{{"Rp " . number_format($bonus, 0, ',', '.')}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="total">
                            <td>Total Pendapatan:</td>
                            <td colspan="3">{{"Rp " . number_format($total, 0, ',', '.')}}</td>
                        </tr>
                        </table>
                </table>
            </div>
        @endforeach
    
</div>
<script>
    window.onload = function() {
        window.print();
    }
    window.onafterprint = function(event) {
        // Kembali ke URL sebelumnya menggunakan window.history
        window.history.back();
    };
</script>
</body>
</html>
