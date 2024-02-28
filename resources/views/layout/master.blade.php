<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Web Penggajian Karyawan">
	<meta name="author" content="Nustra Studio">
	<meta name="keywords" content="">

  <title>Absensi Tulunganggung</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <!-- End fonts -->
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <!-- end plugin css -->

  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}" class="sidebar-dark">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  @include('sweetalert::alert')
  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <div class="main-wrapper" id="app">
    @include('layout.sidebar')
    <div class="page-wrapper">
      @include('layout.header')
      <div class="page-content">
        @yield('content')
        @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}'
            });
        </script>
        
    @endif
      </div>
      <script>
        // Tambahkan event listener untuk tombol atau tautan
        document.addEventListener('DOMContentLoaded', function () {
            var deleteButtons = document.getElementsByClassName('delete-button');
            var approve = document.getElementsByClassName('approve-button');
            Array.from(deleteButtons).forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var formId = this.getAttribute('data-form-delete');
      
                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Tindakan ini tidak dapat diurungkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mengirimkan request penghapusan
                            document.getElementById('form-delete-' + formId).submit();
                        }
                    });
                });
            });
            Array.from(approve).forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var formId = this.getAttribute('data-form-approve');
      
                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Tindakan ini tidak dapat diurungkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#690',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, approve!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mengirimkan request penghapusan
                            document.getElementById('form-approve-' + formId).submit();
                        }
                    });
                });
            });
        });
      </script>
      @include('layout.footer')
    </div>
  </div>

    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
</body>
</html>