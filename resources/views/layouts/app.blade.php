<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ringkasan') · Stokita</title>
    <style>
        :root{font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;color:#17221e;background:#f5f7f5;font-synthesis:none}
        *{box-sizing:border-box}body{margin:0}a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}
        .shell{min-height:100vh;display:grid;grid-template-columns:248px 1fr}.sidebar{background:#11251f;color:#c3d1ca;padding:28px 18px;display:flex;flex-direction:column}
        .brand{display:flex;align-items:center;gap:12px;color:#fff;font-weight:750;font-size:19px;padding:0 12px 34px}.brand-mark{background:#b8f36b;color:#14251e;width:36px;height:36px;display:grid;place-items:center;border-radius:12px;font-weight:900}
        .nav-label{font-size:10px;letter-spacing:1.6px;color:#73867d;padding:8px 13px 10px}.nav{display:grid;gap:5px}.nav a{padding:11px 13px;border-radius:10px;font-size:13px;display:flex;align-items:center;gap:12px}.nav a:hover,.nav a.active{background:#203a30;color:#fff}.nav-icon{font-size:15px;width:17px;text-align:center}.side-bottom{margin-top:auto;border-top:1px solid #2a4037;padding:18px 10px 0;font-size:12px}.side-user{color:white;font-weight:650;margin-bottom:4px}.role-tag{color:#8eaa9b}
        .main{min-width:0}.topbar{height:74px;background:#fff;border-bottom:1px solid #e8ede9;display:flex;align-items:center;justify-content:space-between;padding:0 36px}.crumb{font-size:13px;color:#66746d}.crumb strong{color:#17221e;font-weight:650}.top-actions{display:flex;align-items:center;gap:15px}.top-actions span{font-size:12px;color:#66746d}.logout{border:1px solid #e1e8e3;background:white;border-radius:8px;padding:8px 12px;font-size:12px;cursor:pointer}
        .content{max-width:1280px;padding:32px 36px 56px;margin:auto}.page-head{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;margin-bottom:25px}.eyebrow{color:#668073;font-size:11px;font-weight:700;letter-spacing:1.1px;text-transform:uppercase;margin-bottom:8px}.page-head h1{font-size:27px;line-height:1.15;margin:0;letter-spacing:-.7px}.sub{color:#7a8780;font-size:13px;margin:8px 0 0}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#174d3c;color:white;border:1px solid #174d3c;border-radius:9px;padding:10px 15px;font-size:12px;font-weight:650;cursor:pointer}.btn:hover{background:#103d30}.btn-light{background:#fff;color:#405048;border-color:#dfe7e2}.btn-danger{background:#fff;color:#b43e39;border-color:#edd5d3}.btn-small{padding:7px 10px;font-size:11px}
        .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:22px}.card,.panel{background:#fff;border:1px solid #e8ede9;border-radius:13px}.stat{padding:19px 20px}.stat-top{display:flex;justify-content:space-between;align-items:center;color:#718078;font-size:12px}.stat-mark{width:30px;height:30px;display:grid;place-items:center;border-radius:9px;background:#edf5ee;color:#275f46}.stat-value{font-size:27px;font-weight:730;letter-spacing:-.7px;margin:14px 0 3px}.stat-note{font-size:11px;color:#89958e}
        .panel{padding:20px;margin-bottom:20px}.panel-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:14px}.panel-title{font-size:14px;font-weight:700}.panel-hint{font-size:11px;color:#89958e;margin-top:4px}
        .table-wrap{overflow-x:auto}table{border-collapse:collapse;width:100%;font-size:12px;text-align:left}th{font-size:10px;color:#849189;font-weight:650;letter-spacing:.5px;text-transform:uppercase;padding:11px 12px;border-bottom:1px solid #e9eeeb;white-space:nowrap}td{padding:13px 12px;border-bottom:1px solid #f0f3f1;color:#38463f;vertical-align:middle}tr:last-child td{border-bottom:0}.product-name{font-weight:650;color:#25332c}.muted{color:#8b9790;font-size:11px;margin-top:3px}.badge{display:inline-flex;padding:5px 8px;border-radius:99px;background:#eaf5ee;color:#276446;font-size:10px;font-weight:700}.badge-out{background:#fbefed;color:#b5443d}.badge-warn{background:#fff4df;color:#a86f12}.badge-neutral{background:#f0f3f1;color:#66746d}
        .grid-2{display:grid;grid-template-columns:1.3fr 1fr;gap:18px}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.field{display:grid;gap:7px}.field-full{grid-column:1/-1}.field label{font-size:11px;font-weight:650;color:#526158}.field input,.field select,.field textarea,.search input{width:100%;border:1px solid #dfe7e2;border-radius:8px;padding:10px 11px;background:#fff;color:#24312a;outline:none;font-size:12px}.field input:focus,.field select:focus,.field textarea:focus,.search input:focus{border-color:#5c977b;box-shadow:0 0 0 3px #e6f2eb}.field textarea{min-height:90px;resize:vertical}.help{font-size:10px;color:#8b9790}.form-actions{display:flex;justify-content:flex-end;gap:9px;margin-top:20px}
        .toolbar{display:flex;gap:9px;align-items:center}.search{display:flex;gap:8px;max-width:380px;flex:1}.search input{min-width:180px}.filters{display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap}.filters .field{min-width:140px}.filters .field input,.filters .field select{min-width:140px}.alert{padding:12px 15px;border-radius:9px;margin-bottom:16px;font-size:12px}.alert-success{background:#e8f4eb;color:#276446}.alert-error{background:#fff0ee;color:#a83c36}.error-list{color:#a83c36;font-size:11px;margin:0;padding-left:17px}.empty{text-align:center;padding:35px;color:#849189;font-size:12px}.pagination{display:flex;justify-content:space-between;align-items:center;padding-top:14px;font-size:11px;color:#7e8a82}.pagination nav{display:flex;gap:5px}.pagination a,.pagination span{padding:7px 9px;border:1px solid #e2e9e4;border-radius:6px}.pagination .active span{background:#174d3c;color:#fff;border-color:#174d3c}.pagination svg{width:14px}
        .login-page{min-height:100vh;display:grid;place-items:center;padding:24px;background:radial-gradient(ellipse at 15% 15%,#dfefdf 0,transparent 38%),#f5f7f5}.login-box{width:min(420px,100%);background:#fff;border:1px solid #e6ece8;border-radius:18px;padding:34px;box-shadow:0 16px 55px #18352b12}.login-box .brand{color:#17221e;padding:0 0 27px}.login-box h1{font-size:23px;margin:0 0 8px}.login-box .sub{margin:0 0 24px}.login-box .field{margin-bottom:15px}.login-box .btn{width:100%;margin-top:5px}.login-foot{margin-top:20px;border-top:1px solid #edf1ee;padding-top:15px;font-size:10px;color:#87938c;line-height:1.7}
        @media(max-width:950px){.shell{grid-template-columns:205px 1fr}.stats{grid-template-columns:repeat(2,1fr)}.content{padding:26px 22px}.topbar{padding:0 22px}.grid-2{grid-template-columns:1fr}}
        @media(max-width:650px){.shell{display:block}.sidebar{padding:13px 14px;position:sticky;top:0;z-index:5}.brand{padding:0 4px 12px}.nav-label,.side-bottom{display:none}.nav{display:flex;overflow-x:auto}.nav a{flex:none;padding:9px 10px;font-size:11px}.nav-icon{display:none}.topbar{height:55px;padding:0 16px}.top-actions span{display:none}.content{padding:23px 15px 40px}.page-head{align-items:flex-start;flex-direction:column}.page-head h1{font-size:24px}.stats{gap:9px}.stat{padding:14px}.stat-value{font-size:22px}.stat-top{font-size:10px}.form-grid{grid-template-columns:1fr}.field-full{grid-column:auto}.panel{padding:15px}.search{max-width:none}.toolbar{width:100%}}
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">S</span><span>stokita<span style="color:#a8e867">.</span></span></a>
        <div class="nav-label">MENU UTAMA</div>
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">⌂</span> Ringkasan</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}"><span class="nav-icon">▦</span> Daftar produk</a>
            <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.*') ? 'active' : '' }}"><span class="nav-icon">⇄</span> Stok masuk / keluar</a>
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"><span class="nav-icon">▤</span> Laporan</a>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('users.register') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}"><span class="nav-icon">＋</span> Daftarkan akun</a>
            @endif
        </nav>
        <div class="side-bottom"><div class="side-user">{{ auth()->user()->name }}</div><div class="role-tag">{{ ['admin' => 'Administrator', 'gudang' => 'Petugas gudang', 'kasir' => 'Kasir'][auth()->user()->role] ?? 'Pengguna' }}</div></div>
    </aside>
    <main class="main">
        <header class="topbar">
            <div class="crumb">Stokita <span style="color:#c6d0ca">/</span> <strong>@yield('title', 'Ringkasan')</strong></div>
            <div class="top-actions">
                <span>{{ now()->format('d/m/Y') }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout" type="submit">Keluar ↗</button></form>
            </div>
        </header>
        <section class="content">
            @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
            @if ($errors->any())<div class="alert alert-error"><ul class="error-list">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </section>
    </main>
</div>
</body>
</html>
