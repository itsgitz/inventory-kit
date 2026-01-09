<div>
    <flux:heading size="xl">Create Supplier</flux:heading>
    <flux:subheading>Add a new supplier</flux:subheading>

    <div class="mt-10">
        <form wire:submit="save">
            <div class="mb-6">
                <div class="max-w-md">
                    <flux:input
                        wire:model="form.name"
                        :label="__('Name')"
                        description="Enter a unique name for this supplier"
                        placeholder="e.g, ACME Corp"
                        required />
                </div>
            </div>

            <div class="mb-6">
                <div class="max-w-md">
                    <flux:input
                        wire:model="form.email"
                        :label="__('Email')"
                        type="email"
                        placeholder="e.g, supplier@example.com"
                        required />
                </div>
            </div>

            <div class="mb-6">
                <div class="max-w-md">
                    <flux:input
                        wire:model="form.phone"
                        :label="__('Phone')"
                        placeholder="e.g, +1234567890"
                        required />
                </div>
            </div>

            <div class="mb-6">
                <div class="max-w-md">
                    <flux:textarea
                        wire:model="form.address"
                        :label="__('Address')"
                        description="Provide the full address"
                        placeholder="123 Supplier St, City, Country"
                        required />
                </div>
            </div>

            <div class="mb-6">
                <div class="max-w-md">
                    <flux:input
                        wire:model="form.image"
                        type="file"
                        accept="image/*"
                        :label="__('Image')"
                        description="Upload supplier image (optional, max 2MB)" />
                    @if($form->image)
                        <div class="mt-2">
                            <img src="{{ $form->image->temporaryUrl() }}" alt="Preview" class="h-32 w-32 object-cover rounded">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Submit button ... --}}
            <div class="flex gap-3">
                <flux:button
                    type="submit"
                    variant="primary">
                    Create supplier
                </flux:button>

                <flux:button
                    :href="route('suppliers.manager')"
                    variant="ghost"
                    wire:navigate>
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</div>
