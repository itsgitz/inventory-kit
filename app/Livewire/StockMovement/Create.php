<?php

namespace App\Livewire\StockMovement;

use App\Livewire\Forms\StockMovement\CreateForm;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    public CreateForm $form;

    public function mount()
    {
        $this->form->type = 'IN';
    }

    public function save()
    {
        $this->validate();

        $product = Product::findOrFail($this->form->product_id);

        // Validate stock availability for OUT movements
        if ($this->form->type === 'OUT' && $product->current_stock < $this->form->quantity) {
            $this->addError('form.quantity', 'Insufficient stock. Available: ' . $product->current_stock);
            return;
        }

        // Use database transaction to ensure data integrity
        DB::transaction(function () use ($product) {
            StockMovement::create([
                'product_id' => $this->form->product_id,
                'user_id' => Auth::id(),
                'type' => $this->form->type,
                'quantity' => $this->form->quantity,
                'reason' => $this->form->reason,
                'notes' => $this->form->notes,
            ]);
        });

        session()->flash('success', 'Stock movement recorded successfully!');

        return $this->redirectRoute('stock-movements.manager', navigate: true);
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
