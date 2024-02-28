<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GajiModel;
use App\AbsenModel;

class LemburController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = [
            'id_pegawai'=>$request->id_pegawai,
            'jumlah'=>$request->jumlah,
            'status'=>'lembur'
        ];
        GajiModel::create($data);
        AbsenModel::where('id',$request->id)->update([
            'keterangan'=>'lembur_approve',
        ]);
        return redirect()->route('absen.lembur')->with('success','Data Berhasil Diperbarui');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        AbsenModel::where('id',$id)->update([
            'keterangan'=>'',
        ]);
        return redirect()->route('absen.lembur')->with('success','Data Berhasil Diperbarui');
    }
}
