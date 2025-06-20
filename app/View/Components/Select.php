<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * The size variant.
     */
    public string $size;

    public function __construct(string $size = 'base')
    {
        $this->size = $size;
    }

    public function render(): View
    {
        return view('components.select');
    }
} 