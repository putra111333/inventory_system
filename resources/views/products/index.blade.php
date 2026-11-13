@extends('layouts.app')

@section('title', 'Daftar produk')

@section('content')
    <div class="page-head">
        <div><div class="eyebrow">KATALOG</div><h1>Daftar produk</h1><p class="sub">Kelola informasi produk dan pantau jumlah persediaan.</p></div>
        @if (auth()->user()->role === 'admin')<a class="btn" href="{{ route('products.create') }}">＋ Tambah produk</a>@endif
    </div>
    <section class="panel">
        <div class="panel-head">
            <div><div class="panel-title">Semua produk</div><div class="panel-hint">{{ $products->total() }} produk terdaftar</div></div>
            <form class="search" method="GET" action="{{ route('products.index') }}"><input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, SKU, atau kategori"><button class="btn btn-light btn-small" type="submit">Cari</button></form>
        </div>
        <div class="table-wrap"><table><thead><tr><th>SKU / Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Min. stok</th><th>Kode</th>@if (auth()->user()->role === 'admin')<th>Aksi</th>@endif</tr></thead><tbody>
            @forelse ($products as $product)
                <tr>
                    <td><div class="product-name">{{ $product->name }}</div><div class="muted">{{ $product->code }}</div></td>
                    <td>{{ $product->category ?: '—' }}</td>
                    <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $product->stock <= $product->minimum_stock ? 'badge-warn' : '' }}">{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}</span></td>
                    <td>{{ number_format($product->minimum_stock, 0, ',', '.') }} {{ $product->unit }}</td>
                    <td><a class="btn btn-light btn-small" href="{{ route('products.codes', $product) }}">QR / Barcode</a></td>
                    @if (auth()->user()->role === 'admin')
                        <td><div class="toolbar"><a class="btn btn-light btn-small" href="{{ route('products.edit', $product) }}">Edit</a><form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE')<button class="btn btn-danger btn-small" type="submit">Hapus</button></form></div></td>
                    @endif
                </tr>
            @empty<tr><td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}" class="empty">Belum ada produk yang cocok dengan pencarian.</td></tr>@endforelse
        </tbody></table></div>
        @if ($products->hasPages())<div class="pagination"><span>Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }}</span>{{ $products->links() }}</div>@endif
    </section>
@endsection
