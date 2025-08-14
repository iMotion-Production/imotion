@extends('public/layouts/base')

@php
    $expertise = [
        [
            'title' => 'Quick and Reliable',
            'description' => 'With our 24-48 hours turnaround time you\'ll have your designs when you need them',
        ],
        [
            'title' => 'Conveniently Flexible',
            'description' => 'Our Process is Fast, simple, affordable, and stress-free',
        ],
        [
            'title' => 'No Hidden Fees',
            'description' => 'Pay one flat rate each month with no hidden costs',
        ],
    ];

    $choseUs = [
        [
            'title' => 'Create Unique Products',
            'description' => 'Sell a wide variety of items using our 250+ products with custom printing.',
        ],
        [
            'title' => 'Global Delivery',
            'description' => 'Use fast and flexible worldwide delivery methods to reach customers all over the globe.',
        ],
        [
            'title' => 'Fast & Friendly Support',
            'description' => 'We\'re here to help on live chat from 9 to 5 every weekday, plus a help centre full of step-by-step tutorials to get you started.',
        ],
    ];

    $portfolio = [
        [
            'title' => 'Custom Product',
            'folder' => 'custom-product',
            'total' => 7,
        ],
        [
            'title' => 'Billboard & Banner',
            'folder' => 'banner',
            'total' => 3,
        ],
        [
            'title' => 'Corporate Diary & Notebooks',
            'folder' => 'notebook',
            'total' => 3,
        ],
        [
            'title' => 'Merchandise',
            'folder' => 'merchandise',
            "total" => 5
        ],
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <x-breadcrumb title="Printing Design" />
    <section class="container pb-8 sm:pb-12 lg:pb-20 pt-5 sm:pt-7 lg:pt-12">
        <div class="text-center text-foreground max-w-3xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                Focus on selling while we handle printing and shipping. Only pay after making a sale!
            </h1>
            <p class="mt-1.5 sm:mt-3 text-base sm:text-lg text-muted max-w-2xl mx-auto">
                Our network of print on demand labs has been designed to make your business simpler, more competitive, and more profitable.
            </p>
        </div>

        <img src="/assets/printing-design/base.png" alt="Printing Design" class="w-full h-full object-cover max-w-5xl mx-auto mt-6">
    </section>
    <section class="container pb-10 sm:pb-14 lg:pb-24">
        <div class="mt-12 lg:mt-16 grid grid-cols-1 xl:grid-cols-2 gap-12 xl:gap-16 items-center">
            <img class="w-full h-auto max-w-2xl" src="/assets/printing-design/expertise.png" alt="Printing Design Expertise">
            <div class="max-w-3xl mx-auto">
                <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                    Our Expertise
                </span>
                <h2 class="mt-1.5 sm:mt-3 text-2xl sm:text-3xl font-bold text-foreground">
                    We give you a personal professional graphic designer to help you with all of your creative needs.
                </h2>
                <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-3xl">
                    Have your designs and love them too. Get matched with a vetted professional designer.
                </p>
                <div class="space-y-2 sm:space-y-4 pt-4">
                    @foreach ($expertise as $item)
                        <div class="flex items-center gap-4">
                            <div class="size-8 rounded-full bg-accent-2 text-background flex-shrink-0 flex items-center justify-center">
                                <x-icon name="ri-check-line" class="size-5" />
                            </div>
                            <div>
                                <h4 class="text-xl sm:text-2xl font-bold text-foreground">{{ $item['title'] }}</h4>
                                <p class="text-base sm:text-lg text-muted mt-1">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section class="relative">
        <div class="container pb-10 sm:pb-14 lg:pb-24">
            <div class="text-left text-foreground max-w-3xl">
                <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                    Why Choose Us
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mt-3">
                    Our Impressive quality and diverse designs from Us
                </h1>
                <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-2xl">
                    With our handy product designer you can quickly print custom merchandise and generate mockups with a click of a button.
                </p>
            </div>
            <div class="relative z-10 mt-12 lg:mt-16 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <img class="w-full h-auto max-w-2xl" src="/assets/printing-design/chose-us.png" alt="Printing Design Chose Us">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                        Discover corporate clothing, merchandise items & more for your business
                    </h2>
                    <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-3xl">
                        Our Business helps corporate customers make their brands stand out. It's a great print method for corporate identity, and also for accessories and corporate gift
                    </p>
                    <div class="space-y-4 pt-4">
                        @foreach ($choseUs as $item)
                            <div class="flex items-center gap-4">
                                <div class="size-8 rounded-full bg-accent-2 text-background flex-shrink-0 flex items-center justify-center">
                                    <x-icon name="ri-check-line" class="size-5" />
                                </div>
                                <div>
                                    <h4 class="text-xl sm:text-2xl font-bold text-foreground">{{ $item['title'] }}</h4>
                                    <p class="text-base sm:text-lg text-muted mt-1">{{ $item['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <img src="/assets/printing-design/bags.png" alt="Decorator bags" aria-hidden="true" class="absolute -top-16 right-16 w-md pointer-events-none z-0 hidden xl:block">
    </section>
    <section class="container pb-10 sm:pb-14 lg:pb-24">
        <div class="text-left text-foreground max-w-3xl">
            <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                Our Portfolio
            </span>
            <h1 class="text-4xl md:text-5xl font-bold mt-3">
                Let's See Our Best Work
            </h1>
            <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-2xl">
                We Impress your organization & customers with customization options, and enjoy easy customer and order management.
            </p>
        </div>
        <div class="mt-12 lg:mt-16">
            @foreach ($portfolio as $item)
                <div class="mt-12 lg:mt-16 text-center">
                    <div class="inline-block bg-accent-1 text-base font-semibold text-background py-2 px-6 rounded-tl-xl rounded-br-xl mb-4 mx-auto">
                        {{ $item['title'] }}
                    </div>
                    <div class="grid-masonry mt-6 lg:mt-8">
                    @for ($i = 1; $i <= $item['total']; $i++)
                        <img
                            src="/assets/printing-design/{{ $item['folder'] }}/{{ $i }}.png"
                            alt="{{ $item['title'] }} {{ $i }}"
                            class="grid-masonry-item mb-5 w-auto h-auto"
                        >
                    @endfor
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
