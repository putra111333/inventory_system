@extends('layouts.app')

@section('title', 'Ringkasan')

@section('content')
    <div class="page-head">
        <div><div class="eyebrow">OVERVIEW INVENTORY</div><h1>Ringkasan persediaan</h1><p class="sub">Pantau kondisi produk dan aktivitas stok hari ini.</p></div>
        <a class="btn" href="{{ route('stock.index') }}">＋ Catat transaksi stok</a>
    </div>
    <div class="stats">
        <article class="card stat"><div class="stat-top"><span>Total produk</span><span class="stat-mark">▦</span></div><div class="stat-value">{{ number_format($productCount, 0, ',', '.') }}</div><div class="stat-note">Jenis produk terdaftar</div></article>
        <article class="card stat"><div class="stat-top"><span>Unit tersedia</span><span class="stat-mark">▤</span></div><div class="stat-value">{{ number_format($totalStock, 0, ',', '.') }}</div><div class="stat-note">Total stok seluruh produk</div></article>
        <article class="card stat"><div class="stat-top"><span>Perlu restok</span><span class="stat-mark" style="background:#fff4df;color:#a86f12">!</span></div><div class="stat-value">{{ number_format($lowStockCount, 0, ',', '.') }}</div><div class="stat-note">Stok mencapai batas minimum</div></article>
        <article class="card stat"><div class="stat-top"><span>Transaksi hari ini</span><span class="stat-mark">⇄</span></div><div class="stat-value">{{ number_format($movementCount, 0, ',', '.') }}</div><div class="stat-note">Stok masuk dan keluar</div></article>
    </div>
    <div class="grid-2">
        <section class="panel">
            <div class="panel-head"><div><div class="panel-title">Aktivitas stok terbaru</div><div class="panel-hint">Transaksi terakhir pada sistem</div></div><a class="btn btn-light btn-small" href="{{ route('reports.index') }}">Lihat laporan →</a></div>
            <div class="table-wrap">
                <table><thead><tr><th>Produk</th><th>Tipe</th><th>Jumlah</th><th>Petugas</th></tr></thead><tbody>
                @forelse ($recentMovements as $movement)
                    <tr><td><div class="product-name">{{ $movement->product->name }}</div><div class="muted">{{ $movement->created_at->format('d/m/Y H:i') }}</div></td><td><span class="badge {{ $movement->type === 'out' ? 'badge-out' : '' }}">{{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}</span></td><td>{{ $movement->type === 'in' ? '+' : '−' }}{{ number_format($movement->quantity, 0, ',', '.') }} {{ $movement->product->unit }}</td><td>{{ $movement->user->name }}</td></tr>
                @empty<tr><td colspan="4" class="empty">Belum ada transaksi stok.</td></tr>@endforelse
                </tbody></table>
            </div>
        </section>
        <section class="panel">
            <div class="panel-head"><div><div class="panel-title">Perhatian stok</div><div class="panel-hint">Produk pada atau di bawah batas minimum</div></div><a class="btn btn-light btn-small" href="{{ route('products.index') }}">Semua produk</a></div>
            <div class="table-wrap"><table><thead><tr><th>Produk</th><th>Stok</th><th>Minimum</th></tr></thead><tbody>
                @forelse ($lowStockProducts as $product)
                    <tr><td><div class="product-name">{{ $product->name }}</div><div class="muted">{{ $product->code }}</div></td><td><span class="badge badge-warn">{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}</span></td><td>{{ number_format($product->minimum_stock, 0, ',', '.') }}</td></tr>
                @empty<tr><td colspan="3" class="empty">Semua stok berada di atas batas minimum.</td></tr>@endforelse
            </tbody></table></div>
        </section>
    </div>
@endsection
