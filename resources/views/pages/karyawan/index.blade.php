@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
  @php
      use App\CabangModel;
      use App\ShiftModel;
      use App\GajiModel;
      use App\PotonganModel;
  @endphp
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Data Karyawan</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data Karyawan</h4>
            <a href="{{ url('/database/karyawan/create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
        </div>
      <div class="card-body">
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}

        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                {{-- tabel head nama	kepalaCabang	telepon	alamat	category	keterangan --}}
                <th>No</th>
                <th>Nama</th>
                <th>Cabang</th>
                <th>Jabatan</th>
                <th>Gaji Pokok</th>
                <th>Uang Makan</th>
                <th>Uang Bensin</th>
                <th>Terlambat</th>
                <th>Shift</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tb-category">
              @foreach ($data as $item)
              <tr>
                  @php
                      $cabang = CabangModel::where('id',$item->id_cabang)->first();
                      $potongan = PotonganModel::where('id_pegawai', $item->id)->where('status', 'terlambat')->first();
                      $gaji = GajiModel::where('id_pegawai',$item->id)->where('status','gaji_pokok')->first();
                      $makan = GajiModel::where('id_pegawai',$item->id)->where('status','uang_makan')->first();
                      $bensin = GajiModel::where('id_pegawai',$item->id)->where('status','uang_bensin')->first();
                      $gaji = "Rp " . number_format($gaji->jumlah,0,',','.');
                      $makan = "Rp " . number_format($makan->jumlah,0,',','.');
                      $bensin = "Rp " . number_format($bensin->jumlah,0,',','.');
                      $potongan =$potongan->jumlah ."%";
                      $shift = ShiftModel::where('id',$item->id_shift)->first();
                  @endphp
                <td>{{ $loop->index+1 }}</td>
                <td> {{$item->nama}}</td>
                <td> {{$cabang->nama}}</td>
                <td> {{$item->jabatan}}</td>
                <td>{{$gaji}}</td>
                <td>{{$makan}}</td>
                <td>{{$bensin}}</td>
                <td>{{$potongan}}</td>
                <td>{{$shift->name}}</td>
                <td>
                  <div class="text-end">
                    <a href="/database/karyawan/{{$item->id}}/edit" class="btn btn-primary btn-sm">Edit</a>
                    <form id="form-delete-{{ $item->id }}" action="{{ route('karyawan.destroy', $item->id) }}" method="POST" style="display: none;">
                      @csrf
                      @method('DELETE')
                  </form>
                  <button type="submit" class="btn btn-danger btn-sm delete-button" data-form-delete="{{ $item->id }}">Delete</button>
                  </div>
              </tr>
          @endforeach

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
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
