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
            // Memastikan data absen lengkap
            if (count($item) >= 6 && $item[4] !== null && $item[5] !== null) {
                // Parse waktu absen dengan format jam:menit
                $absen_masuk = Carbon::createFromFormat('H:i', $item[4]);
                $absen_masuk = $absen_masuk->format('H:i');
                $absen_pulang = Carbon::createFromFormat('H:i', $item[5]);
                $absen_pulang = $absen_pulang->format('H:i');
                $absen = new AbsenModel;
    
                $id_shift = KaryawanModel::where('id_absen',$item[0])->value('id_shift');
                $khusus =  KaryawanModel::where('id_absen',$item[0])->value('jabatan');
                $shift_masuk = Carbon::parse(ShiftModel::where('id',$id_shift)->value('jam_masuk'));
                $shift_pulang = Carbon::parse(ShiftModel::where('id',$id_shift)->value('jam_pulang'));
    
                if($absen_masuk <= $shift_masuk && $absen_pulang <= $shift_pulang){
                    $absen->id_pegawai= $item[0];
                    $absen->tanggal = $item[3];
                    $absen->absen_masuk = $absen_masuk;
                    $absen->absen_pulang = $absen_pulang;
                    $absen->status ='tepat_waktu';
                    $absen->save();
                }
                elseif($khusus === "lapangan"){
                    $absen->id_pegawai= $item[0];
                    $absen->tanggal = $item[3];
                    $absen->absen_masuk = $absen_masuk;
                    $absen->absen_pulang = $absen_pulang;
                    $absen->status ='lapangan';
                    $absen->save();   
                }
                else{
                    $absen->id_pegawai= $item[0];
                    $absen->tanggal = $item[3];
                    $absen->absen_masuk = $absen_masuk;
                    $absen->absen_pulang = $absen_pulang;
                    $absen->status ='tidak_tepat_waktu';
                    $absen->save();
                }
            } else {
                // Tindakan yang diambil jika data absen tidak lengkap
                // Misalnya, log pesan kesalahan atau lakukan tindakan pemulihan
                // Berikan pesan kesalahan atau lakukan tindakan yang sesuai di sini
                // Contoh: Log pesan kesalahan
                \Log::error('Data absen tidak lengkap: ' . json_encode($item));
            }
        }
    }
}
