<?php

namespace App\Livewire\StockMovement;

use App\Models\StockMovement;
use App\Services\StockMovementService;
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

    protected StockMovementService $stockMovementService;

    public function boot(StockMovementService $stockMovementService)
    {
        $this->stockMovementService = $stockMovementService;
    }

    #[Title('Stock Movements')]
    public function render()
    {
        $stockMovements = $this->getStockMovements();

        // Pre-calculate ending stocks for all movements to avoid N+1 queries
        $endingStocks = $this->stockMovementService->calculateEndingStocks($stockMovements);

        return view('livewire.stock-movement.manager', [
            'stockMovements' => $stockMovements,
            'endingStocks' => $endingStocks,
        ]);
    }

    public function getStockMovements()
    {
        return $this->stockMovementService->getStockMovements(
            $this->search,
            $this->filterType,
            $this->sortField,
            $this->sortDirection,
            $this->perPage
        );
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
     * Get ending stock for a specific movement (used in view).
     */
    public function getEndingStock(StockMovement $movement, array $endingStocks): int
    {
        return $this->stockMovementService->getEndingStock($movement, $endingStocks);
    }
}
