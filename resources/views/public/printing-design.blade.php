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
<main class="bg-background overflow-hidden">
    <x-breadcrumb title="Printing Design" />
    <section data-aos="fade-up" class="container pt-5 sm:pt-7 lg:pt-12">
        <div class="text-center text-foreground max-w-3xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                Focus on selling while we handle printing and shipping. Only pay after making a sale!
            </h1>
            <p class="mt-1.5 sm:mt-3 text-muted max-w-2xl mx-auto">
                Our network of print on demand labs has been designed to make your business simpler, more competitive, and more profitable.
            </p>
        </div>
        <img src="/assets/printing-design/base.png" alt="Printing Design" class="w-full h-full object-cover max-w-5xl mx-auto mt-6">
    </section>
    <section class="container pt-6 sm:pt-10 lg:pt-16">
        <div class="mt-10 lg:mt-14 grid grid-cols-1 xl:grid-cols-2 gap-12 xl:gap-16 items-center">
            <img data-aos="fade-right" class="w-full h-auto max-w-2xl mx-auto" src="/assets/printing-design/expertise.png" alt="Printing Design Expertise">
            <div data-aos="fade-left" class="space-y-2 sm:space-y-4 max-w-2xl mx-auto">
                <x-badge title="Our Expertise" />
                <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                    We give you a personal professional graphic designer to help you with all of your creative needs.
                </h2>
                <p class="text-muted">
                    Have your designs and love them too. Get matched with a vetted professional designer.
                </p>
                <ul class="space-y-2 sm:space-y-4">
                    @foreach ($expertise as $item)
                        <li class="flex items-center gap-4">
                            <div class="size-8 rounded-full bg-accent-2 text-background flex-shrink-0 flex items-center justify-center">
                                <x-icon name="ri-check-line" class="size-5" />
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-lg sm:text-xl font-bold text-foreground">{{ $item['title'] }}</h4>
                                <p class="text-muted">{{ $item['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    <section class="relative">
        <div class="container pt-12 sm:pt-16 lg:pt-28">
            <x-title data-aos="fade-right" class="max-w-4xl" badge="Why Choose Us" heading="Our Impressive quality and diverse designs from Us" description="With our handy product designer you can quickly print custom merchandise and generate mockups with a click of a button." />
            <div class="relative z-10 mt-10 lg:mt-14 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <img data-aos="fade-right" class="w-full h-auto max-w-2xl mx-auto" src="/assets/printing-design/chose-us.png" alt="Printing Design Chose Us">
                <div data-aos="fade-left" class="space-y-2 sm:space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                        Discover corporate clothing, merchandise items & more for your business
                    </h2>
                    <p class="text-muted">
                        Our Business helps corporate customers make their brands stand out. It's a great print method for corporate identity, and also for accessories and corporate gift
                    </p>
                    <ul class="space-y-2 sm:space-y-4">
                        @foreach ($choseUs as $item)
                            <li class="flex items-center gap-4">
                                <div class="size-8 rounded-full bg-accent-2 text-background flex-shrink-0 flex items-center justify-center">
                                    <x-icon name="ri-check-line" class="size-5" />
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-lg sm:text-xl font-bold text-foreground">{{ $item['title'] }}</h4>
                                    <p class="text-muted">{{ $item['description'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <img data-aos="fade-left" src="/assets/printing-design/bags.png" alt="Decorator bags" aria-hidden="true" class="absolute top-0 right-16 w-md pointer-events-none z-0 hidden xl:block">
    </section>
    <section class="container py-10 sm:py-14 lg:py-24">
        <x-title data-aos="fade-left" class="max-w-4xl" badge="Our Portfolio" heading="Let's See Our Best Work" description="We Impress your organization & customers with customization options, and enjoy easy customer and order management." />
        <div class="mt-10 lg:mt-14">
            @foreach ($portfolio as $item)
                <div class="mt-10 lg:mt-14 text-center" data-aos="fade-up">
                    <x-badge :title="$item['title']" />
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
