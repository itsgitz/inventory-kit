<div>
    <div class="mb-6">
        <flux:heading size="xl">Supplier Management</flux:heading>
        <flux:subheading>Manage your inventory suppliers</flux:subheading>
    </div>

    {{-- Success message --}}
    @if (session()->has('success'))
    <div class="mb-6 max-w-2xl">
        <x-alert
            type="success"
            :message="session('success')" />
    </div>
    @endif

    {{-- Search and Actions Bar --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
        <div class="flex gap-2 w-full sm:max-w-md">
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down" variant="outline">{{ $perPage }}</flux:button>

                <flux:menu>
                    <flux:menu.item wire:click="$set('perPage', 5)">5</flux:menu.item>
                    <flux:menu.item wire:click="$set('perPage', 10)">10</flux:menu.item>
                    <flux:menu.item wire:click="$set('perPage', 25)">25</flux:menu.item>
                    <flux:menu.item wire:click="$set('perPage', 50)">50</flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Search suppliers..."
                icon="magnifying-glass"
                class="flex-1" />
        </div>

        <flux:button
            href="/suppliers/create"
            variant="primary"
            icon="plus"
            wire:navigate>
            Create Supplier
        </flux:button>
    </div>

    {{-- Suppliers Table --}}
    @if ($suppliers->count() > 0)
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Email & Phone
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Address
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Created
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach ($suppliers as $supplier)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors" wire:key="supplier-{{ $supplier->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $supplier->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 overflow-hidden">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $supplier->email }}
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-500">
                            {{ $supplier->phone }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">
                            {{ $supplier->address }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $supplier->created_at->format('M d, Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex gap-2 justify-end">
                            <flux:button
                                :href="route('suppliers.edit', $supplier)"
                                variant="ghost"
                                size="sm"
                                icon="pencil"
                                wire:navigate>
                                Edit
                            </flux:button>

                            <flux:button
                                wire:click="confirmDeletion('{{ $supplier->id }}')"
                                variant="danger"
                                size="sm"
                                icon="trash">
                                Delete
                            </flux:button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $suppliers->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="text-center py-12">
        <flux:icon.users class="mx-auto h-12 w-12 text-zinc-400" />
        <flux:heading size="lg" class="mt-4">
            @if ($search)
            No suppliers found
            @else
            No suppliers yet
            @endif
        </flux:heading>
        <flux:subheading class="mt-2">
            @if ($search)
            Try adjusting your search terms
            @else
            Get started by creating your first supplier
            @endif
        </flux:subheading>

        @if (!$search)
        <div class="mt-6">
            <flux:button
                href="/suppliers/create"
                variant="primary"
                icon="plus"
                wire:navigate>
                Create Supplier
            </flux:button>
        </div>
        @endif
    </div>
    @endif

    <flux:modal name="delete-supplier" wire:model="showingDeleteModal" class="min-w-[22rem]">
        <form wire:submit="deleteSupplier" class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Supplier?</flux:heading>

                <flux:subheading>
                    Are you sure you want to delete <strong>{{ $supplierBeingDeleted?->name }}</strong>? This action cannot be undone.
                    <br><br>
                    Please type <strong>delete {{ $supplierBeingDeleted?->name }}</strong> to confirm.
                </flux:subheading>
            </div>

            <flux:input
                wire:model.live="confirmName"
                :placeholder="'delete ' . ($supplierBeingDeleted?->name ?? '')" />

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger" :disabled="$confirmName !== 'delete ' . ($supplierBeingDeleted?->name ?? '')">
                    Delete Supplier
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
