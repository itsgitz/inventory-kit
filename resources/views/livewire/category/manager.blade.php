<div>
    <div class="mb-6">
        <flux:heading size="xl">Category Management</flux:heading>
        <flux:subheading>Manage your inventory product categories</flux:subheading>
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
        <div class="w-full sm:w-96">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Search categories..."
                icon="magnifying-glass" />
        </div>

        <flux:button
            href="/categories/create"
            variant="primary"
            icon="plus"
            wire:navigate>
            Create Category
        </flux:button>
    </div>

    {{-- Categories Table --}}
    @if ($categories->count() > 0)
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Products
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
                @foreach ($categories as $category)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors" wire:key="category-{{ $category->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-zinc-900 dark:text-white">
                            {{ $category->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400 max-w-md truncate">
                            {{ $category->description }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <flux:badge color="zinc" size="sm">
                            {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                        </flux:badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $category->created_at->format('M d, Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex gap-2 justify-end">
                            <flux:button
                                href="/categories/{{ $category->id }}/edit"
                                variant="ghost"
                                size="sm"
                                icon="pencil"
                                wire:navigate>
                                Edit
                            </flux:button>

                            <flux:button
                                wire:click="delete('{{ $category->id }}')"
                                wire:confirm="Are you sure you want to delete this category?"
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
        {{ $categories->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="text-center py-12">
        <flux:icon.folder-open class="mx-auto h-12 w-12 text-zinc-400" />
        <flux:heading size="lg" class="mt-4">
            @if ($search)
            No categories found
            @else
            No categories yet
            @endif
        </flux:heading>
        <flux:subheading class="mt-2">
            @if ($search)
            Try adjusting your search terms
            @else
            Get started by creating your first category
            @endif
        </flux:subheading>

        @if (!$search)
        <div class="mt-6">
            <flux:button
                href="/categories/create"
                variant="primary"
                icon="plus"
                wire:navigate>
                Create Category
            </flux:button>
        </div>
        @endif
    </div>
    @endif
</div>