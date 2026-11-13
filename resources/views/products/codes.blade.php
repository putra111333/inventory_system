@extends('layouts.app')

@section('title', 'Kode produk')

@section('content')
    <div class="page-head">
        <div><div class="eyebrow">IDENTITAS PRODUK</div><h1>Kode produk</h1><p class="sub">{{ $product->name }} · SKU {{ $product->code }}</p></div>
        <a class="btn btn-light" href="{{ route('products.index') }}">← Kembali ke produk</a>
    </div>
    <section class="code-grid">
        <article class="panel code-card">
            <div class="panel-head"><div><div class="panel-title">QR code</div><div class="panel-hint">Pindai untuk membaca SKU produk</div></div></div>
            <img class="qr-image" src="{{ route('products.codes.qr', $product) }}" alt="QR code untuk {{ $product->code }}">
            <div class="code-value">{{ $product->code }}</div>
            <a class="btn" href="{{ route('products.codes.qr', ['product' => $product, 'download' => 1]) }}>Unduh QR code (.svg)</a>
        </article>
        <article class="panel code-card">
            <div class="panel-head"><div><div class="panel-title">Barcode Code 128</div><div class="panel-hint">Kompatibel dengan scanner barcode umum</div></div></div>
            <img class="barcode-image" src="{{ route('products.codes.barcode', $product) }}" alt="Barcode untuk {{ $product->code }}">
            <div class="code-value">{{ $product->code }}</div>
            <a class="btn" href="{{ route('products.codes.barcode', ['product' => $product, 'download' => 1]) }}>Unduh barcode (.svg)</a>
        </article>
    </section>
    <div class="panel" style="font-size:12px;color:#66746d;line-height:1.7">Kedua kode berisi SKU <strong>{{ $product->code }}</strong>. File SVG dapat dicetak pada label berbagai ukuran tanpa kehilangan ketajaman. Pastikan ukuran cetak barcode cukup lebar agar mudah dipindai.</div>
    <style>
        .code-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.code-card{display:flex;flex-direction:column;align-items:center;text-align:center;min-height:320px}.code-card .panel-head{width:100%;text-align:left}.qr-image{width:230px;height:230px;object-fit:contain;margin:8px 0}.barcode-image{width:min(100%,390px);height:145px;object-fit:contain;margin:48px 0 45px}.code-value{font-size:13px;font-weight:700;letter-spacing:.5px;margin:0 0 18px}.code-card .btn{margin-top:auto}
        @media(max-width:700px){.code-grid{grid-template-columns:1fr}.code-card{min-height:300px}}
        @media print{.sidebar,.topbar,.page-head .btn,.code-card .btn{display:none!important}.shell{display:block}.content{padding:10px}.code-grid{grid-template-columns:1fr 1fr}.panel{break-inside:avoid}.code-card{border:0}}
    </style>
@endsection
