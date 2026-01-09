<div>
    <flux:heading size="xl">Record Stock Movement</flux:heading>
    <flux:subheading>Record a stock in or stock out transaction</flux:subheading>

    <div class="mt-10">
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <flux:select 
                        wire:model="form.product_id" 
                        :label="__('Product')" 
                        placeholder="Select a product"
                        required>
                        @foreach ($products as $product)
                            <flux:select.option :value="$product->id">
                                {{ $product->name }} (Stock: {{ $product->current_stock }})
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select 
                        wire:model="form.type" 
                        :label="__('Type')" 
                        placeholder="Select transaction type"
                        required>
                        <flux:select.option value="IN">Stock In</flux:select.option>
                        <flux:select.option value="OUT">Stock Out</flux:select.option>
                    </flux:select>

                    <flux:input
                        type="number"
                        wire:model="form.quantity"
                        :label="__('Quantity')"
                        description="Number of units"
                        placeholder="1"
                        min="1"
                        required />

                    @if ($form->type === 'OUT' && $form->product_id)
                        @php
                            $selectedProduct = $products->firstWhere('id', $form->product_id);
                            $availableStock = $selectedProduct ? $selectedProduct->current_stock : 0;
                        @endphp
                        @if ($availableStock < $form->quantity)
                            <div class="text-sm text-red-600 dark:text-red-400">
                                Insufficient stock. Available: {{ $availableStock }}
                            </div>
                        @endif
                    @endif
                </div>

                <div class="space-y-6">
                    <flux:input
                        wire:model="form.reason"
                        :label="__('Reason')"
                        description="Required: Explain why this stock movement occurred"
                        placeholder="e.g., Restocked from supplier, Customer sale, Damaged goods return"
                        required />

                    <flux:textarea
                        wire:model="form.notes"
                        :label="__('Notes')"
                        description="Optional: Additional details about this transaction"
                        placeholder="Add any additional notes or comments..."
                        rows="4" />
                </div>
            </div>

            <div class="mt-10 flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary"
                    :disabled="$form->type === 'OUT' && $form->product_id && ($products->firstWhere('id', $form->product_id)?->current_stock ?? 0) < $form->quantity">
                    Record Transaction
                </flux:button>

                <flux:button
                    :href="route('stock-movements.manager')"
                    variant="ghost"
                    wire:navigate>
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</div>
