@extends('layouts.app')

@section('title', $product->exists ? 'Edit produk' : 'Tambah produk')

@section('content')
    <div class="page-head"><div><div class="eyebrow">KATALOG PRODUK</div><h1>{{ $product->exists ? 'Edit produk' : 'Tambah produk' }}</h1><p class="sub">Lengkapi informasi produk. Perubahan stok dicatat melalui transaksi stok.</p></div><a class="btn btn-light" href="{{ route('products.index') }}">← Kembali</a></div>
    <section class="panel">
        <form method="POST" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}">
            @csrf
            @if ($product->exists)@method('PUT')@endif
            <div class="form-grid">
                <div class="field"><label for="code">SKU / KODE PRODUK *</label><input id="code" name="code" value="{{ old('code', $product->code) }}" required maxlength="50" placeholder="Contoh: BRG-001"></div>
                <div class="field"><label for="name">NAMA PRODUK *</label><input id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150" placeholder="Nama produk"></div>
                <div class="field"><label for="category">KATEGORI</label><input id="category" name="category" value="{{ old('category', $product->category) }}" maxlength="100" placeholder="Contoh: Bahan baku"></div>
                <div class="field"><label for="unit">SATUAN *</label><input id="unit" name="unit" value="{{ old('unit', $product->unit ?: 'pcs') }}" required maxlength="30" placeholder="pcs, kg, dus"></div>
                <div class="field"><label for="price">HARGA SATUAN (RP) *</label><input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price ?? '0') }}" required></div>
                <div class="field"><label for="minimum_stock">BATAS MINIMUM STOK *</label><input id="minimum_stock" name="minimum_stock" type="number" min="0" step="1" value="{{ old('minimum_stock', $product->minimum_stock ?? 0) }}" required></div>
                @if ($product->exists)<div class="field"><label>STOK SAAT INI</label><input value="{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}" disabled><span class="help">Ubah stok melalui menu Stok masuk / keluar.</span></div>@endif
                <div class="field field-full"><label for="description">DESKRIPSI</label><textarea id="description" name="description" maxlength="2000" placeholder="Informasi tambahan tentang produk">{{ old('description', $product->description) }}</textarea></div>
            </div>
            <div class="form-actions"><a class="btn btn-light" href="{{ route('products.index') }}">Batal</a><button class="btn" type="submit">Simpan produk</button></div>
        </form>
    </section>
@endsection
