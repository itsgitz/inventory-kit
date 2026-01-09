<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
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
}
