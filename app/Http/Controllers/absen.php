<?php

namespace App\Http\Controllers;
use App\CabangModel;
use App\GajiModel;
use App\AbsenModel;
use Excel;
use App\Imports\InteractiveImport;
use App\Imports\SolutionsImport;
use Illuminate\Http\Request;


class absen extends Controller
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
    public function lembur(){
        return view('pages.cabang.lembur');
    }
    public function penyesuaian(){
        
    }
    public function excel(Request $request,$id){
        $mesin_absen = CabangModel::find($id);
        $mesin_absen = $mesin_absen->mesin_absen;
        if($mesin_absen === "solution"){
            try {
                Excel::import(new SolutionsImport, request()->file('file'));
                return redirect()->back()->with('success', 'Data Imported');
            } catch (\Exception $e) {
                // Handle the exception
                return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
            }
        }
        elseif($mesin_absen === "interactive"){
            try {
                Excel::import(new InteractiveImport, request()->file('file'));
                return redirect()->back()->with('success', 'Data Imported');
            } catch (\Exception $e) {
                // Handle the exception
                return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
            }
        }
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
        AbsenModel::where('id',$id)->delete();
        return redirect()->route('cabang.index')->with('success','Data Berhasil Dihapus');
    }
}
