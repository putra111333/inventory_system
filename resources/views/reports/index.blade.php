@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="page-head"><div><div class="eyebrow">ANALISIS PERSEDIAAN</div><h1>Laporan stok</h1><p class="sub">Rekap stok masuk dan keluar dengan filter periode serta produk.</p></div><button class="btn btn-light" type="button" onclick="window.print()">Cetak laporan ↗</button></div>
    <section class="panel">
        <form class="filters" method="GET" action="{{ route('reports.index') }}">
            <div class="field"><label for="from">DARI TANGGAL</label><input id="from" name="from" type="date" value="{{ $filters['from'] ?? '' }}"></div>
            <div class="field"><label for="to">SAMPAI TANGGAL</label><input id="to" name="to" type="date" value="{{ $filters['to'] ?? '' }}"></div>
            <div class="field"><label for="type">JENIS</label><select id="type" name="type"><option value="">Semua transaksi</option><option value="in" @selected(($filters['type'] ?? '') === 'in')>Stok masuk</option><option value="out" @selected(($filters['type'] ?? '') === 'out')>Stok keluar</option></select></div>
            <div class="field"><label for="product_id">PRODUK</label><select id="product_id" name="product_id"><option value="">Semua produk</option>@foreach ($products as $product)<option value="{{ $product->id }}" @selected(($filters['product_id'] ?? '') == $product->id)>{{ $product->name }}</option>@endforeach</select></div>
            <button class="btn" type="submit">Terapkan filter</button><a class="btn btn-light" href="{{ route('reports.index') }}">Reset</a>
        </form>
    </section>
    <div class="stats" style="grid-template-columns:repeat(2,minmax(0,1fr))">
        <article class="card stat"><div class="stat-top"><span>Total kuantitas stok masuk</span><span class="stat-mark">↘</span></div><div class="stat-value">{{ number_format($incomingTotal, 0, ',', '.') }}</div><div class="stat-note">Unit berdasarkan filter terpilih</div></article>
        <article class="card stat"><div class="stat-top"><span>Total kuantitas stok keluar</span><span class="stat-mark" style="background:#fbefed;color:#b5443d">↗</span></div><div class="stat-value">{{ number_format($outgoingTotal, 0, ',', '.') }}</div><div class="stat-note">Unit berdasarkan filter terpilih</div></article>
    </div>
    <section class="panel">
        <div class="panel-head"><div><div class="panel-title">Detail transaksi</div><div class="panel-hint">{{ $movements->total() }} catatan sesuai filter</div></div></div>
        <div class="table-wrap"><table><thead><tr><th>Waktu</th><th>Produk</th><th>Jenis</th><th>Jumlah</th><th>Referensi / Catatan</th><th>Petugas</th></tr></thead><tbody>
            @forelse ($movements as $movement)
                <tr><td>{{ $movement->created_at->format('d/m/Y H:i') }}</td><td><div class="product-name">{{ $movement->product->name }}</div><div class="muted">{{ $movement->product->code }}</div></td><td><span class="badge {{ $movement->type === 'out' ? 'badge-out' : '' }}">{{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}</span></td><td>{{ number_format($movement->quantity, 0, ',', '.') }} {{ $movement->product->unit }}</td><td>{{ $movement->reference ?: ($movement->note ?: '—') }}</td><td>{{ $movement->user->name }}</td></tr>
            @empty<tr><td colspan="6" class="empty">Tidak ada transaksi pada periode yang dipilih.</td></tr>@endforelse
        </tbody></table></div>
        @if ($movements->hasPages())<div class="pagination"><span>Menampilkan {{ $movements->firstItem() }}–{{ $movements->lastItem() }} dari {{ $movements->total() }}</span>{{ $movements->links() }}</div>@endif
    </section>
    <style>@media print{.sidebar,.topbar,.page-head button,.filters,.pagination{display:none!important}.shell{display:block}.content{padding:0;max-width:none}.panel,.card{break-inside:avoid;border-color:#ccc}.stats{margin-top:15px}}</style>
@endsection
