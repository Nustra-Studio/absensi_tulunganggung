@extends('layout.master')
@php
    use Carbon\Carbon;
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
            <div class="btn-group" role="group" aria-label="Filter Status">
                <input type="checkbox" id="filterAll" name="filterStatus[]" class="btn-check" autocomplete="off" checked>
                <label class="btn btn-primary m-1" for="filterAll">Semua</label>

                <input type="checkbox" id="filterOnTime" name="filterStatus[]" class="btn-check" autocomplete="off">
                <label class="btn btn-primary m-1" for="filterOnTime">Tepat Waktu</label>

                <input type="checkbox" id="filterLate" name="filterStatus[]" class="btn-check" autocomplete="off">
                <label class="btn btn-primary m-1" for="filterLate">Terlambat</label>

                <input type="checkbox" id="filterOvertime" name="filterStatus[]" class="btn-check" autocomplete="off">
                <label class="btn btn-primary m-1" for="filterOvertime">Lembur</label>
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
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tb-category">
                @foreach ($absen as $item)
                @php
                    $start_time = Carbon::createFromFormat('H:i', $item->absen_masuk);
                    $end_time = $sm ? Carbon::createFromFormat('H:i', $sm) : null;
                    $minutes_difference = $end_time ? $end_time->diffInMinutes($start_time) : null;

                    $telat = $minutes_difference * $gpm;
                    $finalValue = $gaji->jumlah - $telat;
                    $finalValue = ($finalValue == $gaji->jumlah) ? 0 : $finalValue;
                @endphp

                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ "Rp " . number_format($gaji->jumlah, 0, ',', '.') }}</td>
                    <td>{{ "Rp " . number_format($telat, 0, ',', '.') }} / {{$minutes_difference}}</td>
                    <td>{{ $item->absen_masuk }}</td>
                    <td>{{ $item->absen_pulang ?? 0 }}</td>
                    <td>{{$item->keterangan}}</td>
                </tr>
            @endforeach

              </tbody>
          </table>
        </div>
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
