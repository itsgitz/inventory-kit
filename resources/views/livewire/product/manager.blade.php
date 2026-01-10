<div>
    <div class="mb-6">
        <flux:heading size="xl">{{ __('Product Management') }}</flux:heading>
        <flux:subheading>{{ __('Manage your inventory products') }}</flux:subheading>
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
                :placeholder="__('Search products...')"
                icon="magnifying-glass"
                class="flex-1" />
        </div>

        <flux:button
            href="/products/create"
            variant="primary"
            icon="plus"
            wire:navigate>
            {{ __('Create Product') }}
        </flux:button>
    </div>

    {{-- Products Table --}}
    @if ($products->count() > 0)
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Image') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Code') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Name') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Category') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Price') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Stock') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach ($products as $product)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors" wire:key="product-{{ $product->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img 
                            src="{{ $product->getImageUrl() }}" 
                            alt="{{ $product->name }}"
                            class="h-10 w-10 rounded-full object-cover"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=random&color=fff&size=128&bold=true&format=svg'">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-mono text-zinc-600 dark:text-zinc-400">
                            {{ $product->code }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-zinc-900 dark:text-white">
                            {{ $product->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $product->category?->name ?? __('N/A') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            ${{ number_format($product->unit_price, 2) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="{{ $product->current_stock > 10 ? 'green' : 'red' }}" size="sm">
                            {{ $product->current_stock }}
                        </flux:badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex gap-2 justify-end">
                            <flux:button
                                :href="route('products.edit', $product)"
                                variant="ghost"
                                size="sm"
                                icon="pencil"
                                wire:navigate>
                                {{ __('Edit') }}
                            </flux:button>

                            <flux:button
                                wire:click="confirmDeletion('{{ $product->id }}')"
                                variant="danger"
                                size="sm"
                                icon="trash">
                                {{ __('Delete') }}
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
        {{ $products->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="text-center py-12">
        <flux:icon.folder-open class="mx-auto h-12 w-12 text-zinc-400" />
        <flux:heading size="lg" class="mt-4">
            @if ($search)
            {{ __('No products found') }}
            @else
            {{ __('No products yet') }}
            @endif
        </flux:heading>
        <flux:subheading class="mt-2">
            @if ($search)
            {{ __('Try adjusting your search terms') }}
            @else
            {{ __('Get started by creating your first product') }}
            @endif
        </flux:subheading>

        @if (!$search)
        <div class="mt-6">
            <flux:button
                href="/products/create"
                variant="primary"
                icon="plus"
                wire:navigate>
                {{ __('Create Product') }}
            </flux:button>
        </div>
        @endif
    </div>
    @endif

    <flux:modal name="delete-product" wire:model="showingDeleteModal" class="min-w-[22rem]">
        <form wire:submit="deleteProduct" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete Product?') }}</flux:heading>

                <flux:subheading>
                    {!! __('Are you sure you want to delete :name? This action cannot be undone.', ['name' => '<strong>' . e($productBeingDeleted?->name ?? '') . '</strong>']) !!}
                    <br><br>
                    {!! __('Please type delete :name to confirm.', ['name' => '<strong>' . e($productBeingDeleted?->name ?? '') . '</strong>']) !!}
                </flux:subheading>
            </div>

            <flux:input
                wire:model.live="confirmName"
                :placeholder="'delete ' . ($productBeingDeleted?->name ?? '')" />

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger" :disabled="$confirmName !== 'delete ' . ($productBeingDeleted?->name ?? '')">
                    {{ __('Delete Product') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
