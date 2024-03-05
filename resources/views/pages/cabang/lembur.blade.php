@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
@php
    use App\CabangModel;
    use App\AbsenModel;
    use App\KaryawanModel;
    use App\ShiftModel;
    use App\GajiModel;
    use Carbon\Carbon;
    $absen = AbsenModel::where('keterangan','lembur')->get();

@endphp
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Absen Lembur</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">

      <div class="card-body">
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                {{-- tabel head nama	kepalaCabang	telepon	alamat	category	keterangan --}}
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Absen Masuk</th>
                <th>Absen Pulang</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tb-category">
              @foreach ($absen as $item)
              <tr>
                  @php
                      $nama = KaryawanModel::where('id_absen',$item->id_pegawai)->value('nama');
                  @endphp
                <td>{{ $loop->index+1 }}</td>
                <td> {{$nama}}</td>
                <td> {{$item->absen_masuk}}</td>
                <td> {{$item->absen_pulang}}</td>
                <td>{{$item->keterangan}}</td>
                <td>{{$item->tanggal}}</td>
                <td>
                  @php
                    $id_pegawai =KaryawanModel::where('id_absen',$item->id_pegawai)->value('id');
                    $gaji = GajiModel::where('id_pegawai',$id_pegawai)->
                            where('status','gaji_pokok')->first();
                    if(!empty($gaji)){
                      $gaji_menit = $gaji->jumlah / 43200;
                    }
                    else{
                      $gaji_menit = 0;
                    }
                    $shift = KaryawanModel::where('id_absen',$item->id_pegawai)->value('id_shift');
                    $shift_pulang = ShiftModel::where('id',$shift)->value('jam_pulang');
                    $shift_pulang = Carbon::parse($shift_pulang);
                    $pulang = Carbon::parse($item->absen_pulang);
                    $selisih = $shift_pulang->diffInMinutes($pulang);
                    $gaji_lembur = $gaji_menit * $selisih;
                    $gaji_lembur =  ceil($gaji_lembur);
                  @endphp
                  <div class="text-end">
                    {{-- <a href="/database/cabang/{{$item->id}}/edit" class="btn btn-primary btn-sm">Edit</a> --}}
                    <form id="form-approve-{{ $item->id }}" action="{{ route('lembur.store', $item->id) }}" method="POST" style="display: none;">
                      @csrf
                      <input type="hidden" name="jumlah" value="{{$gaji_lembur}}">
                      <input type="hidden" name="id_pegawai" value="{{$id_pegawai}}">
                      <input type="hidden" name="id" value="{{$item->id}}">
                  </form>
                    <form id="form-delete-{{ $item->id }}" action="{{ route('lembur.destroy', $item->id) }}" method="POST" style="display: none;">
                      @csrf
                      @method('DELETE')
                  </form>
                  <button type="submit" class="btn btn-success btn-sm approve-button" data-form-approve="{{ $item->id }}">Lembur</button>
                  <button type="submit" class="btn btn-danger btn-sm delete-button" data-form-delete="{{ $item->id }}">Tidak Lembur</button>  
                </div>
              </tr>
          @endforeach
              <!-- Modal -->

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    document.getElementById('showUploadModal').addEventListener('click', function () {
        $('#uploadModal').modal('show');
    });
</script>
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush