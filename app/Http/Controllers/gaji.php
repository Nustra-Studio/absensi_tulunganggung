<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KaryawanModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\AbsenModel;
use App\GajiModel;
use App\ShiftModel;



class gaji extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = KaryawanModel::all();
        return view("pages.gaji.index",compact("data"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tanggal_awal = date("Y-m-01");
        $tanggal_akhir = date("Y-m-t");
        $data = AbsenModel::where('id_pegawai', $id)->get();
        $idd = $id;
        $iddd = KaryawanModel::where('id_absen', $id)->first();
        //->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
        return view("pages.gaji.show",compact("data", "idd", "iddd"));
    }


    public function gaji(Request $request, $id)
    {
        $mo = $request->input('mo');
        $idd = $request->input('idd');
        $status = $request->input('status');
        $keterangan = $request->input('keterangan');

        $item = AbsenModel::where('id_pegawai', $mo)->first();
        $absen = AbsenModel::where('id_pegawai', $idd)->get();

        $absen = AbsenModel::where('id_pegawai', $idd)
            ->whereYear('tanggal', '=', Carbon::parse($mo)->year)
            ->whereMonth('tanggal', '=', Carbon::parse($mo)->month);
        // if ($status) {
        //     $absen->where('status', $status);
        // }
        // if ($keterangan) {
        //     $absen->where('keterangan', $keterangan);
        // }
        $absen = $absen->get();

        // The rest of your code remains unchanged...
        $karyawan = KaryawanModel::where('id_absen', $idd)->first();
        $mshift = $karyawan->id_shift;
        $nshift = ShiftModel::where('id', $mshift)->first();
        $sm = $nshift->jam_masuk;
        $sp = $nshift->jam_pulang;
        $start_time = Carbon::createFromFormat('H:i', $sp);

        // Check if absen_pulang is not NULL before using Carbon::createFromFormat
        $end_time = $sm ? Carbon::createFromFormat('H:i', $sm) : null;
        // for showing the data $minutes_difference ?? 'N/A'
        $minutes_difference = $end_time ? $end_time->diffInMinutes($start_time) : null;
        $start_time = Carbon::createFromFormat('H:i', $nshift->jam_masuk);

        $end_time = Carbon::createFromFormat('H:i', $nshift->jam_pulang);
        $gaji = GajiModel::where('id_pegawai', $id)->first();
        $gaji_pokok = GajiModel::where('id_pegawai', $id)->where('status','gaji_pokok')->value('jumlah');
        $mnt = $end_time ? $end_time->diffInMinutes($start_time) : null;
        $salary_menit = $gaji_pokok / $mnt;
        $fn = $absen->all();
        $tunjangan =GajiModel::where('id_pegawai', $id)
                            ->whereNot('status', 'gaji_pokok')->sum('jumlah');

        // Display the filtered dates (you can remove this line if not needed for debugging)
        return view("pages.gaji.gaji", compact("tunjangan","gaji_pokok","item", "absen", "gaji", "sm", "sp","karyawan","salary_menit"));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function ajaxGaji(Request $request, $id)
    {
        $mo = $request->input('mo');
        $idd = $request->input('idd');
        $status = $request->input('status');
        $keterangan = $request->input('keterangan');
        $absen = AbsenModel::where('id_pegawai', $idd)
            ->whereYear('tanggal', '=', Carbon::parse($mo)->year)
            ->whereMonth('tanggal', '=', Carbon::parse($mo)->month);
        if ($status) {
            $absen->where('status', $status);
        }
        if ($keterangan) {
            $absen->where('keterangan', $keterangan);
        }
        $absen = $absen->get();


        $karyawan = KaryawanModel::where('id_absen', $idd)->first();
        $mshift = $karyawan->id_shift;
        $nshift = ShiftModel::where('id', $mshift)->first();
        $sm = $nshift->jam_masuk;
        $sp = $nshift->jam_pulang;
        $start_time = Carbon::createFromFormat('H:i', $sp);

        // Check if absen_pulang is not NULL before using Carbon::createFromFormat
        $end_time = $sm ? Carbon::createFromFormat('H:i', $sm) : null;
        // for showing the data $minutes_difference ?? 'N/A'
        $minutes_difference = $end_time ? $end_time->diffInMinutes($start_time) : null;
        $start_time = Carbon::createFromFormat('H:i', $nshift->jam_masuk);

        $end_time = Carbon::createFromFormat('H:i', $nshift->jam_pulang);
        $gaji = GajiModel::where('id_pegawai', $id)->first();
        $mnt = $end_time ? $end_time->diffInMinutes($start_time) : null;
        $gpm = intval($gaji->jumlah/$mnt);
        $fn = $absen->all();

        // Return the updated HTML
        return view('pages.gaji.gaji', compact('item', 'absen', 'gaji', 'sm', 'sp', 'gpm'))->render();
    }


}
