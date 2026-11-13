<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · Stokita</title>
    <style>
        :root{font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;color:#17221e;background:#f5f7f5}*{box-sizing:border-box}body{margin:0}.login-page{min-height:100vh;display:grid;place-items:center;padding:24px;background:radial-gradient(ellipse at 15% 15%,#dfefdf 0,transparent 38%),#f5f7f5}.login-box{width:min(420px,100%);background:#fff;border:1px solid #e6ece8;border-radius:18px;padding:34px;box-shadow:0 16px 55px #18352b12}.brand{display:flex;align-items:center;gap:12px;font-weight:750;font-size:19px;padding:0 0 27px}.brand-mark{background:#b8f36b;color:#14251e;width:36px;height:36px;display:grid;place-items:center;border-radius:12px;font-weight:900}.login-box h1{font-size:23px;margin:0 0 8px}.sub{color:#7a8780;font-size:13px;margin:0 0 24px}.field{display:grid;gap:7px;margin-bottom:15px}.field label{font-size:11px;font-weight:650;color:#526158}.field input{width:100%;border:1px solid #dfe7e2;border-radius:8px;padding:11px;background:#fff;font:inherit;font-size:12px;outline:none}.field input:focus{border-color:#5c977b;box-shadow:0 0 0 3px #e6f2eb}.remember{font-size:11px;color:#66746d;display:flex;align-items:center;gap:7px;margin:2px 0 17px}.btn{width:100%;background:#174d3c;color:#fff;border:0;border-radius:9px;padding:12px;font:inherit;font-size:12px;font-weight:650;cursor:pointer}.alert{padding:12px 14px;border-radius:9px;margin-bottom:16px;background:#fff0ee;color:#a83c36;font-size:12px}.login-foot{margin-top:20px;border-top:1px solid #edf1ee;padding-top:15px;font-size:10px;color:#87938c;line-height:1.7}
    </style>
</head>
<body>
<main class="login-page">
    <section class="login-box">
        <div class="brand"><span class="brand-mark">S</span><span>stokita<span style="color:#72a938">.</span></span></div>
        <h1>Selamat datang</h1>
        <p class="sub">Masuk untuk mengelola persediaan Anda.</p>
        @if ($errors->any())<div class="alert">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field"><label for="email">EMAIL</label><input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required autofocus autocomplete="username"></div>
            <div class="field"><label for="password">KATA SANDI</label><input id="password" name="password" type="password" placeholder="Masukkan kata sandi" required autocomplete="current-password"></div>
            <label class="remember"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <button class="btn" type="submit">Masuk ke dashboard →</button>
        </form>
        <div class="login-foot">Akses demo tersedia setelah menjalankan <code>php artisan db:seed</code>. Kredensial kedua akun tercantum di README.</div>
    </section>
</main>
</body>
</html>
