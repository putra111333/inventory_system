<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'productCount' => Product::count(),
            'totalStock' => Product::sum('stock'),
            'lowStockCount' => Product::whereColumn('stock', '<=', 'minimum_stock')->count(),
            'movementCount' => StockMovement::whereDate('created_at', today())->count(),
            'recentMovements' => StockMovement::with(['product', 'user'])
                ->latest()
                ->limit(8)
                ->get(),
            'lowStockProducts' => Product::whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock')
                ->limit(5)
                ->get(),
        ]);
    }
}
