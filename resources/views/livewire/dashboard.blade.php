<div>
    <div class="mb-6">
        <flux:heading size="xl">Inventory Overview</flux:heading>
        <flux:subheading>High-level snapshot of your inventory health</flux:subheading>
    </div>

    {{-- Top Stats --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900 shadow-sm">
            <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                Total Products
            </div>
            <div class="mt-2 text-2xl font-semibold text-zinc-900 dark:text-white">
                {{ number_format($stats['totalProducts']) }}
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900 shadow-sm">
            <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                Categories
            </div>
            <div class="mt-2 text-2xl font-semibold text-zinc-900 dark:text-white">
                {{ number_format($stats['totalCategories']) }}
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900 shadow-sm">
            <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                Suppliers
            </div>
            <div class="mt-2 text-2xl font-semibold text-zinc-900 dark:text-white">
                {{ number_format($stats['totalSuppliers']) }}
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                    Total Stock On Hand
                </div>
            </div>
            <div class="mt-2 text-2xl font-semibold text-zinc-900 dark:text-white">
                {{ number_format($stats['totalStockOnHand']) }}
            </div>
            <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-500">
                Sum of all product stock
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Low Stock Products --}}
        <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 shadow-sm">
            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-800">
                <div>
                    <div class="text-sm font-medium text-zinc-900 dark:text-white">
                        Low Stock Products
                    </div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-500">
                        Based on current stock levels
                    </div>
                </div>
                <flux:button
                    href="{{ route('products.manager') }}"
                    size="xs"
                    variant="outline"
                    icon="arrow-right"
                    wire:navigate>
                    View Products
                </flux:button>
            </div>

            @if ($lowStockProducts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    Product
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    Category
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    Stock
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                            @foreach ($lowStockProducts as $product)
                                @php
                                    $stock = (int) $product->current_stock;

                                    if ($stock <= 0) {
                                        $statusLabel = 'Out of Stock';
                                        $statusColor = 'red';
                                    } elseif ($stock < 10) {
                                        $statusLabel = 'Low Stock';
                                        $statusColor = 'yellow';
                                    } elseif ($stock <= 15) {
                                        $statusLabel = 'Warning';
                                        $statusColor = 'yellow';
                                    } else {
                                        $statusLabel = 'Available';
                                        $statusColor = 'green';
                                    }
                                @endphp
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/80 transition-colors">
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center gap-3">
                                            <img 
                                                src="{{ $product->getImageUrl() }}" 
                                                alt="{{ $product->name }}"
                                                class="h-10 w-10 rounded-full object-cover flex-shrink-0"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=random&color=fff&size=128&bold=true&format=svg'">
                                            <div>
                                                <div class="font-medium text-zinc-900 dark:text-white">
                                                    {{ $product->name }}
                                                </div>
                                                <div class="text-xs text-zinc-500 dark:text-zinc-500">
                                                    {{ $product->code }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $product->category?->name ?? 'Uncategorized' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-zinc-900 dark:text-white">
                                        {{ $stock }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <flux:badge color="{{ $statusColor }}" size="sm">
                                            {{ $statusLabel }}
                                        </flux:badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-4 py-8 text-center">
                    <flux:icon.folder-open class="mx-auto h-10 w-10 text-zinc-400" />
                    <flux:heading size="sm" class="mt-3">
                        No products yet
                    </flux:heading>
                    <flux:subheading class="mt-1">
                        Start by adding your first product to see low stock alerts.
                    </flux:subheading>
                </div>
            @endif
        </div>

        {{-- Recent Stock Movements --}}
        <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 shadow-sm">
            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-800">
                <div>
                    <div class="text-sm font-medium text-zinc-900 dark:text-white">
                        Recent Stock Movements
                    </div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-500">
                        Latest stock in/out transactions
                    </div>
                </div>
                <flux:button
                    href="{{ route('stock-movements.manager') }}"
                    size="xs"
                    variant="outline"
                    icon="arrow-right"
                    wire:navigate>
                    View All
                </flux:button>
            </div>

            @if ($recentMovements->count() > 0)
                <ul class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @foreach ($recentMovements as $movement)
                        <li class="px-4 py-3 flex items-start gap-3">
                            <div class="mt-1">
                                <flux:badge color="{{ $movement->type === 'IN' ? 'green' : 'yellow' }}" size="sm">
                                    {{ $movement->type === 'IN' ? 'In' : 'Out' }}
                                </flux:badge>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                            {{ $movement->product?->name ?? 'Deleted Product' }}
                                        </div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-500">
                                            {{ $movement->product?->code ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">
                                        {{ $movement->type === 'IN' ? '+' : '-' }}{{ $movement->quantity }}
                                    </div>
                                </div>
                                <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-500 flex items-center justify-between">
                                    <span>
                                        {{ $movement->created_at->format('M d, Y h:i A') }}
                                    </span>
                                    <span>
                                        By {{ $movement->user?->name ?? 'Deleted User' }}
                                    </span>
                                </div>
                                <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-500">
                                    Reason: {{ $movement->reason }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-4 py-8 text-center">
                    <flux:icon.folder-open class="mx-auto h-10 w-10 text-zinc-400" />
                    <flux:heading size="sm" class="mt-3">
                        No stock movements yet
                    </flux:heading>
                    <flux:subheading class="mt-1">
                        Record your first stock transaction to see recent activity here.
                    </flux:subheading>
                </div>
            @endif
        </div>
    </div>
</div>

