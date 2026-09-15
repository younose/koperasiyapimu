<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ nama_koperasi() }}</title>
<link rel="icon" href="{{ asset('logo.svg') }}">
<link rel="manifest" href="{{ route('manifest') }}">
<meta name="theme-color" content="#0e6b56">
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
      <div class="topbar-right">
        <div class="who">{!! icon(is_admin()?'lock':'user') !!} {{ is_admin()?'Pengurus':cu()['nama'] }}</div>
        <form method="post" action="{{ route('logout') }}" class="topbar-logout">@csrf<button type="submit" class="ic" aria-label="Keluar">{!! icon('logout') !!}</button></form>
      </div></header>
    <div class="content">
      <div id="pwaInstallBanner" class="install-banner no-print" style="display:none">
        <div class="install-ic">{!! icon('phone') !!}</div>
        <div class="install-txt">
          <div class="install-title">Pasang {{ nama_koperasi() }}</div>
          <div class="install-sub">Akses lebih cepat dari layar utama, tanpa membuka browser.</div>
        </div>
        <button type="button" class="install-btn" id="pwaInstallBtn">Pasang</button>
        <button type="button" class="install-close" id="pwaInstallClose" aria-label="Tutup">{!! icon('x') !!}</button>
      </div>
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
<script>
(function(){
  var banner=document.getElementById('pwaInstallBanner');
  if(!banner) return;
  var btn=document.getElementById('pwaInstallBtn');
  var closeBtn=document.getElementById('pwaInstallClose');
  var deferredPrompt=null;

  function isStandalone(){
    return (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) || window.navigator.standalone===true;
  }
  function isDismissed(){
    try{ return sessionStorage.getItem('pwa_banner_dismissed')==='1'; }catch(e){ return false; }
  }
  function markInstalled(){
    try{ localStorage.setItem('pwa_installed','1'); }catch(e){}
  }
  function alreadyMarkedInstalled(){
    try{ return localStorage.getItem('pwa_installed')==='1'; }catch(e){ return false; }
  }

  if(isStandalone()){ markInstalled(); return; }
  if(alreadyMarkedInstalled()) return;

  window.addEventListener('beforeinstallprompt', function(e){
    e.preventDefault();
    deferredPrompt=e;
    if(!isDismissed()) banner.style.display='flex';
  });

  window.addEventListener('appinstalled', function(){
    markInstalled();
    banner.style.display='none';
    deferredPrompt=null;
  });

  if(btn) btn.addEventListener('click', function(){
    if(!deferredPrompt) return;
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(function(){
      deferredPrompt=null;
      banner.style.display='none';
    }).catch(function(){ banner.style.display='none'; });
  });

  if(closeBtn) closeBtn.addEventListener('click', function(){
    banner.style.display='none';
    try{ sessionStorage.setItem('pwa_banner_dismissed','1'); }catch(e){}
  });

  if('serviceWorker' in navigator){
    navigator.serviceWorker.register('{{ asset("sw.js") }}').catch(function(){});
  }
})();
</script>
@yield('scripts')
</body></html>
