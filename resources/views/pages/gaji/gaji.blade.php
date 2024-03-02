@extends('layout.master')
@php
    use Carbon\Carbon;
    use App\PotonganModel;
@endphp

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
        <div class="card-header text-start">
            <h4 class="card-title">Data Karyawan</h4>
            <div class="btn-group" role="group" aria-label="Basic example">
              <a href="{{url("slipgaji/?mo=$mo")}}"  class="btn btn-primary">cetak all</a>
              <a href="{{url("slipgaji/?mo=$mo&idd=$idd")}}"  class="btn btn-primary">Cetak Perorang</a>
              <form id="form-delete-{{ $idd }}" action="{{ route('gaji.store') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="mo" value="{{$mo}}">
                <input type="hidden" name="idd" value="{{$idd}}">
                <input type="hidden" value="change" name="keterangan">
            </form>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Add</button>
              <button type="button" class="btn btn-danger delete-button" data-form-delete="{{ $idd }}">Clear Lembur</button>
            </div>
        </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
                <tr>
                  <th>No</th>
                  <th>Gaji Utama</th>
                  <th>Potongan Terlambat</th>
                  <th>Lembur</th>
                  <th>Tunjangan</th>
                </tr>
              </thead>
              <tbody id="tb-category">
                @foreach ($absen as $item)
                @php
                    $start_time = Carbon::createFromFormat('H:i', $item->absen_masuk);
                    $end_time = $sm ? Carbon::createFromFormat('H:i', $sm) : null;
                    $minutes_difference = $end_time ? $end_time->diffInMinutes($start_time) : null;
                    $potongan = PotonganModel::where('id_pegawai',$karyawan->id)
                                                ->where('status','terlambat')
                                                ->value('jumlah');
                    if($item->status === "tepat_waktu"){
                        $potongan = 0;
                    }
                    $potongan = $potongan * $gaji_pokok / 100;
                    $end_time = $sp ? Carbon::createFromFormat('H:i', $sp) : null;
                    $finsh_time =$item->absen_pulang ? Carbon::createFromFormat('H:i', $item->absen_pulang) : null;
                    $total_time = $end_time ? $end_time->diffInMinutes($finsh_time ) : null;
                    if ($item->keterangan === "lembur_approve") {
                          $lembur = $salary_menit * $total_time;
                          $total_time = $total_time . " menit"; // Menggabungkan string " menit" ke variabel $total_time
                    } elseif ($item->keterangan === "lembur") {
                        $lembur = 0;
                        $total_time = "Lembur Not approve";
                    } else {
                        $lembur = 0;
                        $total_time = ""; // Kosongkan $total_time
                    }

                    // $telat = $minutes_difference * $gpm;
                    // $finalValue = $gaji->jumlah - $telat;
                    // $finalValue = ($finalValue == $gaji->jumlah) ? 0 : $finalValue;
                @endphp

                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ "Rp " . number_format($gaji->jumlah, 0, ',', '.') }}</td>
                    <td>{{ "Rp " . number_format($potongan, 0, ',', '.') }}</td>
                    <td>{{ "Rp " . number_format($lembur, 0, ',', '.')}} | {{$total_time }} </td>
                    <td>{{ "Rp " . number_format($tunjangan, 0, ',', '.') }}</td>
                </tr>
            @endforeach

              </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form  action="{{ route('gaji.store') }}" method="POST" class="forms-sample">
          @csrf
          <div class="mb-3">
            <label for="exampleInputUsername1" class="form-label">Bonus</label>
            <input type="number" name="bonus" class="form-control" id="exampleInputUsername1">
        </div>
        <div class="mb-3">
          <label for="exampleInputUsername1" class="form-label">Potongan Tambahan</label>
          <input type="number" name="potongan" class="form-control" id="exampleInputUsername1">
      </div>
        <input type="hidden" value="add" name="keterangan">
        <input type="hidden" value="{{$karyawan->id}}" name="id_karyawan">
        <input type="hidden" name="mo" value="{{$mo}}">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </form>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Menangani perubahan status checkbox
        $('input[name="filterStatus[]"]').change(function () {
            var mo = new URLSearchParams(window.location.search).get('mo');
            var idd = new URLSearchParams(window.location.search).get('idd');

            // Menentukan status berdasarkan checkbox yang dipilih
            var status = $(this).attr('id') === 'filterLate' ? 'tidak_tepat_waktu' : 'tepat_waktu';

            // Memeriksa apakah checkbox lembur dicek
            var lemburChecked = $('#filterOvertime').is(':checked');

            // Membuat URL baru dengan parameter yang diperbarui
            var newUrl = window.location.pathname + '?mo=' + mo + '&idd=' + idd;

            // Jika bukan "Semua" dan checkbox lembur tidak dicentang, tambahkan parameter status
            if ($(this).attr('id') !== 'filterAll' && !lemburChecked) {
                newUrl += '&status=' + status;
            }

            // Jika checkbox "Lembur" dicentang, tambahkan parameter lembur
            if (lemburChecked) {
                newUrl += '&status=' + status + '&keterangan=lembur_approve';
            }



            // Memperbarui history URL tanpa me-refresh halaman
            window.history.pushState({ path: newUrl }, '', newUrl);

            // Memeriksa apakah checkbox dicentang atau tidak
            var isChecked = $(this).is(':checked');

            // Mengganti kelas tombol sesuai dengan status checkbox
            $(this).next('label').toggleClass('btn-primary', !isChecked).toggleClass('btn-primary', isChecked);

            // Jika "Semua" dicentang, uncheck checkbox lainnya
            if ($(this).attr('id') === 'filterAll' && isChecked) {
                $('input[name="filterStatus[]"]').not(this).prop('checked', false);
            } else if ($(this).attr('id') !== 'filterAll' && isChecked) {
                // Jika checkbox lain dicentang, uncheck "Semua"
                $('#filterAll').prop('checked', false);

                // Pastikan "Tepat Waktu" dan "Terlambat" tidak bisa dicentang secara bersamaan
                if ($(this).attr('id') === 'filterOnTime') {
                    $('#filterLate').prop('checked', false);
                } else if ($(this).attr('id') === 'filterLate') {
                    $('#filterOnTime').prop('checked', false);
                }
            }

            // Ambil dan perbarui konten menggunakan AJAX
            updateTableContent();
        });

        // Fungsi untuk memperbarui konten tabel menggunakan AJAX
        function updateTableContent() {
            $.ajax({
                url: window.location.href,
                method: 'GET',
                success: function (data) {
                    // Ganti isi tabel dengan konten yang diperbarui
                    $('#tb-category').html($(data).find('#tb-category').html());
                },
                error: function () {
                    console.error('Error fetching data.');
                }
            });
        }
    });
</script>




@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
