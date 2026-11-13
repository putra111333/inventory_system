<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'type' => ['nullable', 'in:in,out'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ]);

        $movements = StockMovement::query()
            ->with(['product', 'user'])
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->whereDate('created_at', '<=', $to))
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->when($filters['product_id'] ?? null, fn ($query, $productId) => $query->where('product_id', $productId));

        return view('reports.index', [
            'movements' => (clone $movements)->latest()->paginate(15)->withQueryString(),
            'incomingTotal' => (clone $movements)->where('type', 'in')->sum('quantity'),
            'outgoingTotal' => (clone $movements)->where('type', 'out')->sum('quantity'),
            'products' => Product::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }
}
