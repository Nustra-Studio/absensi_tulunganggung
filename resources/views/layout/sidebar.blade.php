@php
    use App\CabangModel;
    $data = CabangModel::all();
@endphp
<nav class="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      Nustra<span>Studio</span>
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      {{-- <li class="nav-item nav-category">Main</li> --}}
      {{-- <li class="nav-item {{ active_class(['/']) }}">
        <a href="{{ url('/') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li> --}}
      @if (auth()->user()->role == "admin")
      <li class="nav-item nav-category">Data Master</li>
      <li class="nav-item {{ active_class(['masterdata/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#masterdata" role="button" aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="database"></i>
          <span class="link-title">Master data</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['masterdata/*']) }}" id="masterdata">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('/database/cabang') }}" class="nav-link {{ active_class(['database/cabang']) }}">Cabang</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/database/shift') }}" class="nav-link {{ active_class(['database/shift']) }}">Shift</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/database/karyawan') }}" class="nav-link {{ active_class(['database/pegawai']) }}">Karyawan</a>
            </li>
          </ul>
        </div>
      </li>
      @endif
      <li class="nav-item nav-category">web apps</li>
      <li class="nav-item {{ active_class(['cabang/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#cabang" role="button" aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="book"></i>
          <span class="link-title">Absen</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['cabang/*']) }}" id="cabang">
          <ul class="nav sub-menu">
              @foreach ($data as $item)
              <li class="nav-item">
                <a href="{{ url("absen/cabang/$item->id") }}" class="nav-link {{ active_class(["cabang/$item->id"]) }}">{{$item->nama}}</a>
              </li>
              @endforeach
          </ul>
        </div>
      </li>
      <!-- <li class="nav-item {{ active_class(['lembur/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#lembur" role="button" aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="moon"></i>
          <span class="link-title">Lembur</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['lembur/*']) }}" id="lembur">
          <ul class="nav sub-menu">
              @foreach ($data as $item)
              <li class="nav-item">
                <a href="{{ url("/absen/lembur/$item->id") }}" class="nav-link {{ active_class(["lembur/$item->id"]) }}">{{$item->nama}}</a>
              </li>
              @endforeach
          </ul>
        </div>
      </li> -->
      @if (auth()->user()->role == "admin")
      <li class="nav-item {{ active_class(['/absen/lembur']) }}">
        <a href="{{ url('/absen/lembur') }}" class="nav-link">
          <i class="link-icon" data-feather="moon"></i>
          <span class="link-title">Lembur</span>
        </a>
      </li>

    
      <li class="nav-item {{ active_class(['/gaji']) }}">
        <a href="{{ url('/gaji') }}" class="nav-link">
          <i class="link-icon" data-feather="dollar-sign"></i>
          <span class="link-title">Gaji</span>
        </a>
      </li>
      @endif
      <!-- <li class="nav-item {{ active_class(['Gaji/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#Gaji" role="button" aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="dollar-sign"></i>
          <span class="link-title">Gaji</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['Gaji/*']) }}" id="Gaji">
          <ul class="nav sub-menu">
              @foreach ($data as $item)
              <li class="nav-item">
                <a href="{{ url("gaji/cabang/$item->id") }}" class="nav-link {{ active_class(["cabang/$item->id"]) }}">{{$item->nama}}</a>
              </li>
              @endforeach
          </ul>
        </div>
      </li> -->
  </div>
</nav>