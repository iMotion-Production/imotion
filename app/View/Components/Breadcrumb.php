<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * The breadcrumb title.
     */
    public string $title;

    public array $breadcrumbs;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title)
    {
        $this->title = $title;

        $this->breadcrumbs = [
            [
                'url' => route('service.printing-design'),
                'title' => 'Printing Design',
            ],
            [
                'url' => route('service.web-development'),
                'title' => 'Website Design & Development',
            ],
            [
                'url' => route('service.mobile-application'),
                'title' => 'Mobile Application',
            ],
            [
                'url' => route('service.digital-marketing'),
                'title' => 'Digital Marketing',
            ],
            [
                'url' => route('service.graphic-design'),
                'title' => 'Graphic Design',
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
