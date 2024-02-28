@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
@php
    use App\CabangModel;
    use App\AbsenModel;
    use App\KaryawanModel;
    $datas= CabangModel::where('id',$data->id)->get();
    $absen = AbsenModel::all();

@endphp
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Penyusuaian Lembur</a></li>
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
                <th>Status</th>
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
                <td>{{$item->status}}</td>
                <td>{{$item->keterangan}}</td>
                <td>{{$item->tanggal}}</td>
                <td>
                  <div class="text-end">
                    {{-- <a href="/database/cabang/{{$item->id}}/edit" class="btn btn-primary btn-sm">Edit</a> --}}
                    <form id="form-delete-{{ $item->id }}" action="{{ route('absen.penyusuaianlembur', $item->id) }}" method="POST" style="display: none;">
                      @csrf
                      @method('post')
                  </form>
                  <button type="submit" class="btn btn-danger btn-sm delete-button" data-form-delete="{{ $item->id }}">Bukan Lembur</button>
                  </div>
              </tr>
          @endforeach
              {{-- <!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <form action="{{ url("/absen/add-excel/$data->id") }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
              </div>
              <div class="modal-body">
                  <input type="file" name="file">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload</button>
              </div>
          </form>
      </div>
  </div>
</div> --}}

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