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

@php
    use Carbon\Carbon;
@endphp

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title text-center mt-2">Data Penggajian</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tb-category">
                @php
                $uniqueDates = [];
            @endphp

            @foreach ($data as $item)
                @php
                    $formattedDate = Carbon::parse($item->tanggal)->formatLocalized('%B %Y');
                @endphp

                @if (!in_array($formattedDate, $uniqueDates))
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $formattedDate }}</td>
                        <td>
                            <div class="text-end">
                                <a href="{{ url('/penggajian/'. $iddd->id . "?mo=" . Carbon::parse($item->tanggal)->format('Y-m')) }}&idd={{$idd}}&status=tepat_waktu" class="btn btn-primary btn-sm">View</a>
                            </div>
                        </td>
                    </tr>
                    @php
                        $uniqueDates[] = $formattedDate;
                    @endphp
                @endif
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
