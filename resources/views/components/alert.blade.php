@props([
    'type' => 'info',
    'message' => '',
    'dismissible' => false
])

<div {{ $attributes->merge(['class' => "border rounded-lg p-4 mb-4 flex justify-between items-center gap-3 {$variantClass()}"]) }}>
    <div class="text-sm font-medium flex-1">
        {{ $message }}
        {{ $slot }}
    </div>

    @if($dismissible)
        <button type="button"
                class="shrink-0 p-1 rounded-md hover:bg-white/50 dark:hover:bg-black/20 transition-colors ml-auto"
                onclick="this.parentElement.remove()">
            <span class="text-lg font-light leading-none">&times;</span>
        </button>
    @endif
</div>
