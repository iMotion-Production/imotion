<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Title extends Component
{
    /**
     * Create a new component instance.
     */
    public string $badge;

    public string $heading;

    public ?string $description;

    public ?bool $isCenter;

    public function __construct(string $badge, string $heading, ?string $description = null, ?bool $isCenter = false)
    {
        $this->badge = $badge;
        $this->heading = $heading;
        $this->description = $description;
        $this->isCenter = $isCenter;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.title');
    }
}
