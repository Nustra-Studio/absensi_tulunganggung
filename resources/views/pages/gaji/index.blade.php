@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Shift</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data gaji</h4>
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
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tb-category">
              @foreach ($data as $item)
              <tr>
                <td>{{ $loop->index+1 }}</td>
                <td> {{$item->nama}}</td>
                <td>
                  <div class="text-end">
                    <a href="/gaji/{{$item->id_absen}}" class="btn btn-primary btn-sm">View</a>
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
