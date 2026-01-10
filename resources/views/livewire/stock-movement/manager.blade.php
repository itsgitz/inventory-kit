<div>
    <div class="mb-6">
        <flux:heading size="xl">{{ __('Stock Movements') }}</flux:heading>
        <flux:subheading>{{ __('Track all inventory transactions and stock changes') }}</flux:subheading>
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
                :placeholder="__('Search by product name...')"
                icon="magnifying-glass"
                class="flex-1" />
        </div>

        <div class="flex gap-2">
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down" variant="outline">
                    @if ($filterType === 'all')
                        {{ __('All Types') }}
                    @elseif ($filterType === 'IN')
                        {{ __('Stock In') }}
                    @else
                        {{ __('Stock Out') }}
                    @endif
                </flux:button>

                <flux:menu>
                    <flux:menu.item wire:click="$set('filterType', 'all')">{{ __('All Types') }}</flux:menu.item>
                    <flux:menu.item wire:click="$set('filterType', 'IN')">{{ __('Stock In') }}</flux:menu.item>
                    <flux:menu.item wire:click="$set('filterType', 'OUT')">{{ __('Stock Out') }}</flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            <flux:button
                href="/stock-movements/create"
                variant="primary"
                icon="plus"
                wire:navigate>
                {{ __('Record Transaction') }}
            </flux:button>
        </div>
    </div>

    {{-- Stock Movements Table --}}
    @if ($stockMovements->count() > 0)
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Date') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Product') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Type') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Quantity') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Ending Stock') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('Reason') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        {{ __('User') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach ($stockMovements as $movement)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors" wire:key="movement-{{ $movement->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $movement->created_at->format('M d, Y') }}
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-500">
                            {{ $movement->created_at->format('h:i A') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-zinc-900 dark:text-white">
                            {{ $movement->product?->name ?? __('Deleted Product') }}
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-500">
                            {{ $movement->product?->code ?? __('N/A') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="{{ $movement->type === 'IN' ? 'green' : 'yellow' }}" size="sm">
                            {{ $movement->type === 'IN' ? __('In') : __('Out') }}
                        </flux:badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $movement->quantity }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $this->getEndingStock($movement, $endingStocks) }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400 max-w-md truncate">
                            {{ $movement->reason }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $movement->user?->name ?? __('Deleted User') }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $stockMovements->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="text-center py-12">
        <flux:icon.folder-open class="mx-auto h-12 w-12 text-zinc-400" />
        <flux:heading size="lg" class="mt-4">
            @if ($search || $filterType !== 'all')
            {{ __('No stock movements found') }}
            @else
            {{ __('No stock movements yet') }}
            @endif
        </flux:heading>
        <flux:subheading class="mt-2">
            @if ($search || $filterType !== 'all')
            {{ __('Try adjusting your search terms or filters') }}
            @else
            {{ __('Get started by recording your first stock transaction') }}
            @endif
        </flux:subheading>

        @if (!$search && $filterType === 'all')
        <div class="mt-6">
            <flux:button
                href="/stock-movements/create"
                variant="primary"
                icon="plus"
                wire:navigate>
                {{ __('Record Transaction') }}
            </flux:button>
        </div>
        @endif
    </div>
    @endif
</div>
