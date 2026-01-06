<div>
    <flux:heading size="xl">Category Management</flux:heading>
    <flux:subheading>Manage your inventory product categories</flux:subheading>

    <div class="mt-10">
        @if (session()->has('success'))
        <div class="max-w-md">
            <x-alert
                type="success"
                :message="session('success')"
            />
        </div>
        @endif
        <form wire:submit="save">
            <div class="mb-6">
                <div class="max-w-md">
                    <flux:input
                        wire:model="form.name"
                        :label="__('Name')"
                        :value="old('name')"
                        description="Enter a unique name for this category"
                        placeholder="e.g, Electronics"
                        required
                    />
                </div>
            </div>
            <div class="mb-6">
                <div class="max-w-md">
                    <flux:textarea
                        wire:model="form.description"
                        :label="__('Description')"
                        :value="old('description')"
                        description="Provide a brief description"
                        placeholder="Describe this category ..."
                        required
                    />
                </div>
            </div>

            {{-- Submit button ... --}}
            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Create category
                </flux:button>

                <flux:button
                    href="/categories"
                    variant="ghost"
                    wire:navigate
                >
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</div>
