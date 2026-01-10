<?php

namespace App\Livewire\StockMovement;

use App\Livewire\Forms\StockMovement\CreateForm;
use App\Models\Product;
use App\Services\StockMovementService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    public CreateForm $form;

    protected StockMovementService $stockMovementService;

    public function boot(StockMovementService $stockMovementService)
    {
        $this->stockMovementService = $stockMovementService;
    }

    public function mount()
    {
        $this->form->type = 'IN';
    }

    public function save()
    {
        $this->validate();

        try {
            $this->stockMovementService->create([
                'product_id' => $this->form->product_id,
                'user_id' => Auth::id(),
                'type' => $this->form->type,
                'quantity' => $this->form->quantity,
                'reason' => $this->form->reason,
                'notes' => $this->form->notes,
            ]);

            session()->flash('success', 'Stock movement recorded successfully!');

            return $this->redirectRoute('stock-movements.manager', navigate: true);
        } catch (\Exception $e) {
            $this->addError('form.quantity', $e->getMessage());
            return;
        }
    }

    public function updatedFormType()
    {
        // Reset quantity when type changes
        $this->form->quantity = 1;
    }

    public function updatedFormProductId()
    {
        // Reset quantity when product changes
        $this->form->quantity = 1;
    }

    #[Title('Record Stock Movement')]
    public function render()
    {
        $products = Product::orderBy('name')->get();

        return view('livewire.stock-movement.create', [
            'products' => $products,
        ]);
    }
}
