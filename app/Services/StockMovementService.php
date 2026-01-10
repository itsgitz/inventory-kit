<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    /**
     * Create a new stock movement with validation.
     *
     * @param array $data
     * @return StockMovement
     * @throws \Exception
     */
    public function create(array $data): StockMovement
    {
        $product = Product::findOrFail($data['product_id']);

        // Validate stock availability for OUT movements
        if ($data['type'] === 'OUT' && $product->current_stock < $data['quantity']) {
            throw new \Exception('Insufficient stock. Available: ' . $product->current_stock);
        }

        // Use database transaction to ensure data integrity
        return DB::transaction(function () use ($data) {
            return StockMovement::create($data);
        });
    }

    /**
     * Get paginated stock movements with search, filtering, and sorting.
     *
     * @param string $search
     * @param string $filterType
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getStockMovements(
        string $search = '',
        string $filterType = 'all',
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return StockMovement::query()
            ->with(['product', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->when($filterType !== 'all', function ($query) use ($filterType) {
                $query->where('type', $filterType);
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }

    /**
     * Calculate ending stocks for all movements in the collection.
     * This calculates the running balance correctly by getting all movements
     * up to each movement's timestamp.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $stockMovements
     * @return array
     */
    public function calculateEndingStocks($stockMovements): array
    {
        $endingStocks = [];

        // For each movement in the current page, calculate ending stock
        // by getting all movements up to that point in time
        foreach ($stockMovements as $movement) {
            $productId = $movement->product_id;
            $movementDate = $movement->created_at;
            $movementId = $movement->id;

            // Get all movements for this product up to and including this movement
            $allMovements = StockMovement::where('product_id', $productId)
                ->where(function ($query) use ($movementDate, $movementId) {
                    $query->where('created_at', '<', $movementDate)
                        ->orWhere(function ($q) use ($movementDate, $movementId) {
                            $q->where('created_at', '=', $movementDate)
                                ->where('id', '<=', $movementId);
                        });
                })
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $balance = 0;
            foreach ($allMovements as $m) {
                if ($m->type === 'IN') {
                    $balance += $m->quantity;
                } else {
                    $balance -= $m->quantity;
                }
            }

            $endingStocks[$movement->id] = $balance;
        }

        return $endingStocks;
    }

    /**
     * Get ending stock for a specific movement (used in view).
     *
     * @param StockMovement $movement
     * @param array $endingStocks
     * @return int
     */
    public function getEndingStock(StockMovement $movement, array $endingStocks): int
    {
        return $endingStocks[$movement->id] ?? 0;
    }
}
