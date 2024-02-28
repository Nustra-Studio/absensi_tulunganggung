    @extends('layout.master')
    @php
        use App\CabangModel;
        use App\ShiftModel;

    @endphp
    @section('content')
    <nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/database/karyawan">Karyawan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Karyawan</li>
    </ol>
    </nav>
    <div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">

            <h6 class="card-title">Input Karyawan</h6>
            <form 
            action="{{ route('karyawan.store') }}" 
            method="POST" 
            enctype="multipart/form-data"    
            class="forms-sample">
                @csrf
                @php
                $uniqueValue = hash('sha256', uniqid(mt_rand(), true));

                @endphp
                <input type="text" hidden value="{{$uniqueValue}}" name="uuid">
            <div class="mb-3">
                <label for="exampleInputUsername1" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Nama Karyawan">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="Jabatan Karyawan">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Id Absen</label>
                <input type="number" name="id_absen" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Gaji Per Jam </label>
                <input type="number" name="gaji" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Uang Makan</label>
                <input type="number" name="uang_makan" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Uang bensin</label>
                <input type="number" name="uang_bensin" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Potongan Terlambat</label>
                <input type="number" name="terlambat" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlSelect1" class="form-label">Tugas Di Cabang</label>
                <select class="form-select" name="cabang" id="exampleFormControlSelect1">
                    @php
                        $data = CabangModel::all();
                    @endphp
                        @foreach ($data as $item)
                            <option value="{{$item->id}}">{{$item->nama}}</option>
                        @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlSelect1" class="form-label">Shift</label>
                <select class="form-select" name="shift" id="exampleFormControlSelect1">
                    @php
                        $data = ShiftModel::all();
                    @endphp
                        @foreach ($data as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary me-2">Submit</button>
            <button  
            onclick="window.history.go(-1); return false;"
            type="submit"
            value="Cancel" class="btn btn-secondary">Cancel</button>
            </form>

        </div>
        </div>
    </div>
    </div>

    @endsection
