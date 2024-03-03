<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use App\AbsenModel;
use App\ShiftModel;
use App\KaryawanModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;


class InteractivesImport implements ToCollection
{
    // /**
    // * @param array $row
    // *
    // * @return \Illuminate\Database\Eloquent\Model|null
    // */
    // public function model(array $row)
    // {
    //     // return new AbsenModel([
    //     //     //
    //     // ]);
    // }
     /**
     * @param collection $collection
     *
     * 
    */
    public function collection(Collection $collection){
        $data = $collection->slice(4);
        foreach ($data as $item){
        if (!empty($item[0]) && !empty($item[4]) && !empty($item[5]) && !empty($item[5])) {
            $absen_masuk = Carbon::createFromFormat('H:i', $item[4])->format('H:i');
            $absen_pulang = Carbon::createFromFormat('H:i', $item[5])->format('H:i');

            $absen_masuk_carbon = Carbon::createFromFormat('H:i', $absen_masuk);
            $absen_pulang_carbon = Carbon::createFromFormat('H:i', $absen_pulang);

            $absen = new AbsenModel;

            $id_shift = KaryawanModel::where('id_absen', $item[0])->value('id_shift');
            $khusus = KaryawanModel::where('id_absen', $item[0])->value('jabatan');
            $shift_masuk = Carbon::parse(ShiftModel::where('id', $id_shift)->value('jam_masuk'));
            $shift_pulang = Carbon::parse(ShiftModel::where('id', $id_shift)->value('jam_pulang'));
            if($item[4] != null){
                if($absen_masuk_carbon <= $shift_masuk){
                    if($absen_pulang_carbon < $shift_pulang){
                            $absen->id_pegawai= $item[0];
                            $absen->tanggal = $item[3];
                            $absen->absen_masuk = $item[4];
                            $absen->absen_pulang = $item[5];
                            $absen->status ='tidak_tepat_waktu';
                        
                        $absen->save();
                    }

                    else{
                        if($absen_pulang_carbon > $shift_pulang){
                            $absen->id_pegawai= $item[0];
                            $absen->tanggal = $item[3];
                            $absen->absen_masuk = $item[4];
                            $absen->absen_pulang = $item[5];
                            $absen->keterangan='lembur';
                            $absen->status ='tepat_waktu';
                        }
                        else{
                            $absen->id_pegawai= $item[0];
                            $absen->tanggal = $item[3];
                            $absen->absen_masuk = $item[4];
                            $absen->absen_pulang = $item[5];
                            $absen->status ='tepat_waktu';
                        }
                    $absen->save();
                    }
                }
                elseif($khusus === "lapangan"){
                    if($absen_pulang_carbon > $shift_pulang){
                        $absen->id_pegawai= $item[0];
                        $absen->tanggal = $item[3];
                        $absen->absen_masuk = $item[4];
                        $absen->absen_pulang = $item[5];
                        $absen->keterangan='lembur';
                        $absen->status ='tepat_waktu';
                    }
                    $absen->id_pegawai= $item[0];
                    $absen->tanggal = $item[3];
                    $absen->absen_masuk = $item[4];
                    $absen->absen_pulang = $item[5];
                    $absen->status ='tepat_waktu';
                    $absen->save();   
                }
                else{
                    $absen->id_pegawai= $item[0];
                    $absen->tanggal = $item[3];
                    $absen->absen_masuk = $item[4];
                    $absen->absen_pulang = $item[5];
                    $absen->status ='tidak_tepat_waktu';
                    
                    $absen->save();
                }
            }
            
        }
    }
    }
}
