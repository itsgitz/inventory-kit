<div>
    <flux:heading size="xl">Edit Product</flux:heading>
    <flux:subheading>Update product details</flux:subheading>

    <div class="mt-10">
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <flux:input
                        wire:model="form.name"
                        :label="__('Name')"
                        description="Enter the product name"
                        placeholder="e.g. MacBook Pro"
                        required />

                    <flux:input
                        wire:model="form.code"
                        :label="__('Product Code')"
                        description="A unique identifier for this product"
                        placeholder="e.g. MBP2024-001"
                        required />

                    <flux:select wire:model="form.category_id" :label="__('Category')" placeholder="Select a category">
                        @foreach ($categories as $category)
                            <flux:select.option :value="$category->id">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="form.supplier_id" :label="__('Supplier')" placeholder="Select a supplier">
                        @foreach ($suppliers as $supplier)
                            <flux:select.option :value="$supplier->id">{{ $supplier->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="space-y-6">
                    <flux:input
                        type="number"
                        step="0.01"
                        wire:model="form.unit_price"
                        :label="__('Unit Price')"
                        description="Selling price per unit"
                        placeholder="0.00"
                        required />

                    <flux:input
                        type="number"
                        wire:model="form.current_stock"
                        :label="__('Initial Stock')"
                        description="Current quantity on hand"
                        placeholder="0"
                        required />

                    <flux:textarea
                        wire:model="form.description"
                        :label="__('Description')"
                        description="Provide detailed information about the product"
                        placeholder="Describe this product ..."
                        required />
                </div>
            </div>

            <div class="mt-10 flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary">
                    Update Product
                </flux:button>

                <flux:button
                    :href="route('products.manager')"
                    variant="ghost"
                    wire:navigate>
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</div>
