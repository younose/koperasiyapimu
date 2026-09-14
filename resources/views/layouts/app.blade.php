<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ nama_koperasi() }}</title>
<link rel="icon" href="{{ asset('logo.svg') }}">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head><body>
<div class="shell">
@if(is_admin())
  @php($ks=\App\Support\Ledger::ringkas())
  <aside class="sidebar">
    <div class="brand"><div class="brand-mark"><img src="{{ asset('logo.svg') }}" alt="logo"></div>
      <div><div class="brand-name">{{ nama_koperasi() }}</div><div class="brand-sub">Panel Pengurus</div></div></div>
    <nav class="nav">
      <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard')?'active':'' }}">{!! icon('home') !!}<span>Beranda</span></a>
      <a href="{{ route('anggota.index') }}" class="{{ request()->routeIs('anggota.*')?'active':'' }}">{!! icon('users') !!}<span>Anggota</span></a>
      <a href="{{ route('simpanan.index') }}" class="{{ request()->routeIs('simpanan.*')?'active':'' }}">{!! icon('piggy') !!}<span>Simpanan</span></a>
      <a href="{{ route('kas.index') }}" class="{{ request()->routeIs('kas.*')?'active':'' }}">{!! icon('book') !!}<span>Buku Kas</span></a>
      <a href="{{ route('pinjaman.index') }}" class="{{ request()->routeIs('pinjaman.*')?'active':'' }}">{!! icon('loan') !!}<span>Pinjaman</span></a>
      <a href="{{ route('iuran.index') }}" class="{{ request()->routeIs('iuran.*')?'active':'' }}">{!! icon('bell') !!}<span>Iuran Wajib</span></a>
      <a href="{{ route('shu.index') }}" class="{{ request()->routeIs('shu.*')?'active':'' }}">{!! icon('shu') !!}<span>Pembagian SHU</span></a>
      <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*')?'active':'' }}">{!! icon('doc') !!}<span>Laporan</span></a>
      <a href="{{ route('pengaturan.index') }}" class="{{ request()->routeIs('pengaturan.*')?'active':'' }}">{!! icon('gear') !!}<span>Pengaturan</span></a>
    </nav>
    <div class="side-foot">
      <div class="side-metric"><span>Simpanan</span><b>{{ rupiah(\App\Models\Simpanan::totalAll()) }}</b></div>
      <div class="side-metric"><span>Saldo kas</span><b>{{ rupiah($ks['saldo']) }}</b></div>
      <form method="post" action="{{ route('logout') }}">@csrf<button class="logout" style="width:100%;border:0;cursor:pointer">{!! icon('logout') !!} Keluar</button></form>
    </div>
  </aside>
@else
  <aside class="sidebar">
    <div class="brand"><div class="brand-mark"><img src="{{ asset('logo.svg') }}" alt="logo"></div>
      <div><div class="brand-name">{{ nama_koperasi() }}</div><div class="brand-sub">Portal Anggota</div></div></div>
    <nav class="nav">
      <a href="{{ route('portal') }}" class="{{ request()->routeIs('portal')?'active':'' }}">{!! icon('home') !!}<span>Simpanan Saya</span></a>
      <a href="{{ route('pinjaman-saya') }}" class="{{ request()->routeIs('pinjaman-saya')?'active':'' }}">{!! icon('loan') !!}<span>Pinjaman Saya</span></a>
      <a href="{{ route('akun') }}" class="{{ request()->routeIs('akun')?'active':'' }}">{!! icon('user') !!}<span>Akun Saya</span></a>
    </nav>
    <div class="side-foot">
      <div class="side-metric"><span>Anggota</span><b>{{ cu()['no'] ?? '' }}</b></div>
      <form method="post" action="{{ route('logout') }}">@csrf<button class="logout" style="width:100%;border:0;cursor:pointer">{!! icon('logout') !!} Keluar</button></form>
    </div>
  </aside>
@endif
  <main class="main">
    <header class="topbar"><h1>{{ $title ?? '' }}</h1>
      <div class="who">{!! icon(is_admin()?'lock':'user') !!} {{ is_admin()?'Pengurus':cu()['nama'] }}</div></header>
    <div class="content">
      @if(session('ok'))<div class="alert alert-ok">{{ session('ok') }}</div>@endif
      @if(session('err'))<div class="alert alert-err">{{ session('err') }}</div>@endif
      @if($errors->any())<div class="alert alert-err">{{ $errors->first() }}</div>@endif
      @yield('content')
    </div>
  </main>
</div>
@include('partials.popup')
<script>
function dismissPopup(){var p=document.getElementById('popup');if(!p)return;try{sessionStorage.setItem('pop_'+p.dataset.key,'1');}catch(e){}p.style.display='none';}
(function(){var p=document.getElementById('popup');if(!p)return;var s=false;try{s=sessionStorage.getItem('pop_'+p.dataset.key);}catch(e){}if(!s)p.style.display='grid';})();
function openModal(id){document.getElementById(id).style.display='grid';}
function closeModal(id){document.getElementById(id).style.display='none';}
function rp(n){return 'Rp '+Math.round(n).toLocaleString('id-ID');}
</script>
@yield('scripts')
</body></html>
