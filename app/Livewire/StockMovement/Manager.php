<?php

namespace App\Livewire\StockMovement;

use App\Models\StockMovement;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = 'all';
    public int $perPage = 10;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    #[Title('Stock Movements')]
    public function render()
    {
        $stockMovements = $this->getStockMovements();

        // Pre-calculate ending stocks for all movements to avoid N+1 queries
        $endingStocks = $this->calculateEndingStocks($stockMovements);

        return view('livewire.stock-movement.manager', [
            'stockMovements' => $stockMovements,
            'endingStocks' => $endingStocks,
        ]);
    }

    public function getStockMovements()
    {
        return StockMovement::query()
            ->with(['product', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterType !== 'all', function ($query) {
                $query->where('type', $this->filterType);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    /**
     * Calculate ending stocks for all movements in the collection
     * This calculates the running balance correctly by getting all movements
     * up to each movement's timestamp
     */
    protected function calculateEndingStocks($stockMovements): array
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
     * Get ending stock for a specific movement (used in view)
     */
    public function getEndingStock(StockMovement $movement, array $endingStocks): int
    {
        return $endingStocks[$movement->id] ?? 0;
    }
}
