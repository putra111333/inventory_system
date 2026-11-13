@extends('layouts.app')

@section('title', 'Stok masuk / keluar')

@section('content')
    <div class="page-head"><div><div class="eyebrow">PERGERAKAN PERSEDIAAN</div><h1>Stok masuk / keluar</h1><p class="sub">Setiap transaksi otomatis memperbarui stok dan menyimpan nama petugas.</p></div></div>
    <div class="grid-2">
        <section class="panel">
            <div class="panel-head"><div><div class="panel-title">Catat transaksi</div><div class="panel-hint">Pastikan jumlah dan tipe transaksi sudah benar.</div></div></div>
            <form method="POST" action="{{ route('stock.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="field field-full"><label for="product_id">PRODUK *</label><select id="product_id" name="product_id" required><option value="">Pilih produk</option>@foreach ($products as $product)<option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->code }} — {{ $product->name }} (stok: {{ $product->stock }} {{ $product->unit }})</option>@endforeach</select></div>
                    <div class="field"><label for="type">JENIS TRANSAKSI *</label><select id="type" name="type" required><option value="in" @selected(old('type') === 'in')>Stok masuk</option><option value="out" @selected(old('type') === 'out')>Stok keluar</option></select></div>
                    <div class="field"><label for="quantity">JUMLAH *</label><input id="quantity" name="quantity" type="number" min="1" step="1" value="{{ old('quantity') }}" required placeholder="Jumlah unit"></div>
                    <div class="field field-full"><label for="reference">REFERENSI / NO. DOKUMEN</label><input id="reference" name="reference" value="{{ old('reference') }}" maxlength="100" placeholder="Contoh: PO-2026-001 atau INV-001"></div>
                    <div class="field field-full"><label for="note">CATATAN</label><textarea id="note" name="note" maxlength="1000" placeholder="Catatan transaksi (opsional)">{{ old('note') }}</textarea></div>
                </div>
                <div class="form-actions"><button class="btn" type="submit">Simpan transaksi</button></div>
            </form>
        </section>
        <section class="panel">
            <div class="panel-head"><div><div class="panel-title">Panduan transaksi</div><div class="panel-hint">Alur pencatatan stok</div></div></div>
            <div style="font-size:12px;line-height:1.8;color:#526158">
                <p><span class="badge">Masuk</span> Gunakan untuk pembelian, retur pelanggan, atau penambahan stok lainnya.</p>
                <p><span class="badge badge-out">Keluar</span> Gunakan untuk penjualan, pemakaian, atau barang rusak. Stok tidak dapat menjadi negatif.</p>
                <p>Transaksi menyimpan waktu, petugas, produk, jumlah, dan referensi untuk kebutuhan audit.</p>
            </div>
            <div class="stat" style="background:#f5f8f5;border-radius:10px;margin-top:20px"><div class="stat-top">Jumlah produk terdaftar</div><div class="stat-value">{{ $products->count() }}</div><div class="stat-note">produk aktif</div></div>
        </section>
    </div>
    <section class="panel">
        <div class="panel-head"><div><div class="panel-title">Riwayat transaksi</div><div class="panel-hint">Catatan terbaru stok masuk dan keluar</div></div><a class="btn btn-light btn-small" href="{{ route('reports.index') }}">Lihat laporan →</a></div>
        <div class="table-wrap"><table><thead><tr><th>Waktu</th><th>Produk</th><th>Jenis</th><th>Jumlah</th><th>Referensi</th><th>Petugas</th></tr></thead><tbody>
            @forelse ($movements as $movement)
                <tr><td>{{ $movement->created_at->format('d/m/Y H:i') }}</td><td><div class="product-name">{{ $movement->product->name }}</div><div class="muted">{{ $movement->product->code }}</div></td><td><span class="badge {{ $movement->type === 'out' ? 'badge-out' : '' }}">{{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}</span></td><td>{{ number_format($movement->quantity, 0, ',', '.') }} {{ $movement->product->unit }}</td><td>{{ $movement->reference ?: '—' }}</td><td>{{ $movement->user->name }}</td></tr>
            @empty<tr><td colspan="6" class="empty">Belum ada transaksi stok.</td></tr>@endforelse
        </tbody></table></div>
        @if ($movements->hasPages())<div class="pagination"><span>Menampilkan {{ $movements->firstItem() }}–{{ $movements->lastItem() }} dari {{ $movements->total() }}</span>{{ $movements->links() }}</div>@endif
    </section>
@endsection
