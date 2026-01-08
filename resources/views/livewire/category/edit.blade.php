<div>
    <flux:heading size="xl">Edit Category</flux:heading>
    <flux:subheading>Update category details</flux:subheading>

    <div class="mt-10">
        <form wire:submit="save">
            <div class="mb-6">
                <div class="max-w-md">
                    <flux:input
                        wire:model="form.name"
                        :label="__('Name')"
                        description="Enter a unique name for this category"
                        placeholder="e.g, Electronics"
                        required />
                </div>
            </div>
            <div class="mb-6">
                <div class="max-w-md">
                    <flux:textarea
                        wire:model="form.description"
                        :label="__('Description')"
                        description="Provide a brief description"
                        placeholder="Describe this category ..."
                        required />
                </div>
            </div>

            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary">
                    Update category
                </flux:button>

                <flux:button
                    :href="route('categories.manager')"
                    variant="ghost"
                    wire:navigate>
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</div>
