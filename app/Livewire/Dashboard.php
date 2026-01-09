<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Title('Dashboard')]
    public function render()
    {
        $stats = [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'totalStockMovements' => StockMovement::count(),
            'totalStockOnHand' => Product::sum('current_stock'),
        ];

        $lowStockProducts = Product::with('category')
            ->orderBy('current_stock', 'asc')
            ->limit(5)
            ->get();

        $recentMovements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'lowStockProducts' => $lowStockProducts,
            'recentMovements' => $recentMovements,
        ]);
    }
}

