<div>
    <flux:heading size="xl">Category Management</flux:heading>
    <flux:subheading>Manage your inventory product categories</flux:subheading>

    <div class="mt-10">
        <form wire:submit="save">
            <div class="mb-6">
                <flux:input
                    wire:model="name"
                    label="Name"
                    description="Enter a unique name for this category"
                    placeholder="e.g, Electronics"
                    required
                />
            </div>
            <div class="mb-6">
                <flux:input
                    wire:model="description"
                    label="Description"
                    description="Provide a brief description"
                    placeholder="Describe this category ..."
                    required
                />
            </div>

            {{-- Submit button ... --}}
            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Create category
                </flux:button>
            </div>
        </form>
    </div>
</div>
