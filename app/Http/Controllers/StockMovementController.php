<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(): View
    {
        return view('stock.index', [
            'products' => Product::orderBy('name')->get(),
            'movements' => StockMovement::with(['product', 'user'])->latest()->paginate(12),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000000'],
            'reference' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($data, $request): void {
            $product = Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();

            if ($data['type'] === 'out' && $product->stock < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "Stok {$product->name} hanya {$product->stock} {$product->unit}.",
                ]);
            }

            $product->increment(
                'stock',
                $data['type'] === 'in' ? $data['quantity'] : -$data['quantity']
            );

            StockMovement::create([
                ...$data,
                'user_id' => $request->user()->id,
            ]);
        });

        return redirect()->route('stock.index')->with('success', 'Transaksi stok berhasil dicatat.');
    }
}
