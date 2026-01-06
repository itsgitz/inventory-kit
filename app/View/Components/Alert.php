<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;

    public string $message;

    public bool $dismissible;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = 'info',
        string $message = '',
        bool $dismissible = false
    ) {
        //
        $this->type = $type;
        $this->message = $message;
        $this->dismissible = $dismissible;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }

    public function variantClass(): string
    {
        return match ($this->type) {
            'success' => 'border-lime-500 bg-lime-50 text-lime-900 dark:bg-lime-950 dark:text-lime-100',
            'error' => 'border-red-500 bg-red-50 text-red-900 dark:bg-red-950 dark:text-red-100',
            'warning' => 'border-amber-500 bg-amber-50 text-amber-900 dark:bg-amber-950 dark:text-amber-100',
            default => 'border-blue-500 bg-blue-50 text-blue-900 dark:bg-blue-950 dark:text-blue-100',
        };
    }
}
