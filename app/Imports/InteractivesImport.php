<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use App\AbsenModel;
use App\ShiftModel;
use App\KaryawanModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;


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
            $absen_masuk = strtotime($item[4]);
            $absen_pulang = strtotime($item[5]);
            $absen = new AbsenModel;
            // $absen->tanggal = $item[0];
            // $absen->absen_masuk = $item[1];
            // $absen->absen_pulang = $item[2];
            // $absen->save();
            $id_shift = KaryawanModel::where('id_absen',$item[0])->value('id_shift');
            $khusus =  KaryawanModel::where('id_absen',$item[0])->value('jabatan');
            $shift_masuk = ShiftModel::where('id',$id_shift)->value('jam_masuk');
            $shift_masuk = strtotime($shift_masuk);
            $shift_pulang = ShiftModel::where('id',$id_shift)->value('jam_pulang');
            $shift_masuk = strtotime($shift_pulang);
            if($item[4] != null){
                if($absen_masuk <= $shift_masuk){
                    if($absen_pulang < $shift_pulang){
                            $absen->id_pegawai= $item[0];
                            $absen->tanggal = $item[3];
                            $absen->absen_masuk = $item[4];
                            $absen->absen_pulang = $item[5];
                            $absen->status ='tidak_tepat_waktu';
                        
                        $absen->save();
                    }

                    else{
                        if($absen_pulang > $shift_pulang){
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
                    $absen->id_pegawai= $item[0];
                    $absen->tanggal = $item[3];
                    $absen->absen_masuk = $item[4];
                    $absen->absen_pulang = $item[5];
                    $absen->status ='lapangan';
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
