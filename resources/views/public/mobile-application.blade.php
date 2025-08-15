@extends('public/layouts/base')

@php
    $items = [
        [
            'title' => 'Performance Analytics',
            'description' => 'We measure your business as well as locate user exit points, track the amount of time spent in an app, and construct funnels to track user activity.',
        ],
        [
            'title' => 'Future State Planning',
            'description' => 'Planning for the future state of mobile applications involves considering various trends and steps in their development',
        ],
        [
            'title' => 'Collateral & Content Creation',
            'description' => 'Determine various media and materials used to promote a mobile application and engage its target audience',
        ],
    ];

    $choseUs = [
        [
            'icon' => 'ri-pencil-line',
            'title' => 'Tools for every stack',
            'description' => 'Create effective digital marketing collateral for mobile apps, tools like Foleon, FlippingBook, and other design platforms can be utilized',
        ],
        [
            'icon' => 'ri-function-line',
            'title' => 'Prebuilt integrations',
            'description' => 'Effective mobile app collateral encompasses diverse forms such as informative blog posts, engaging infographics, and compelling landing pages',
        ],
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <x-breadcrumb title="Mobile Application" />
    <section class="container pt-5 sm:pt-7 lg:pt-12">
        <div class="text-center text-foreground max-w-2xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                Our elegant, durable solutions solve business challenges across all touchpoints, screens, and devices.
            </h1>
            <p class="mt-1.5 sm:mt-3 text-muted max-w-xl mx-auto">
                We power business with flexible technology that's structured to scale as brands grow.
            </p>
        </div>
        <div class="mt-10 lg:mt-14 grid grid-cols-1 xl:grid-cols-2 gap-12 xl:gap-16 items-center">
            <img class="w-full h-auto max-w-2xl" src="/assets/mobile-application/base.png" alt="Mobile Application">
            <div class="space-y-2 sm:space-y-4 max-w-2xl mx-auto">
                <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                    We adapt to shifting business priorities and user demands with a dynamic program tailored to your needs.
                </h2>
                <p class="text-muted">
                    From an array of annual support, maintenance, optimization and enhancement services to ensure continuous, incremental improvements.
                </p>
                <ul class="space-y-2 sm:space-y-4">
                    @foreach ($items as $item)
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
    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <x-title class="max-w-6xl" isCenter badge="Our Expertise" heading="We implements the highest standards to ensure your platforms run efficiently, effectively process, and securely communicate with outside networks." />
        <div class="w-full h-full relative max-w-7xl mx-auto mt-6 sm:mt-12">
            <img src="/assets/mobile-application/design.png" alt="Our Expertise Mobile Application" class="w-full h-full object-cover pr-6 sm:pr-10">
            <img src="/assets/mobile-application/design-menu.png" alt="Menu Mobile Application" class="absolute w-full h-auto max-w-32 sm:max-w-40 bottom-3 sm:bottom-5 right-0">
        </div>
    </section>
    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center justify-center">
            <div class="text-left text-foreground max-w-4xl">
                <x-title class="max-w-4xl" badge="Why Choose Us" heading="Born to Develop" description="We develop a different types of content that can help showcase the app's features, benefits, and value proposition." />
                <div class="mt-3 sm:mt-6">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 py-3 px-6 border border-accent-2 font-semibold text-foreground bg-transparent-1 hover:bg-accent-2 hover:text-background rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>Contact Us</span>
                        <x-icon name="ri-messenger-line" class="size-5" />
                    </a>
                </div>
                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6 xs:gap-8 sm:gap-10 lg:gap-14 mt-8">
                    @foreach ($choseUs as $item)
                        <div>
                            <x-icon name="{{ $item['icon'] }}" class="size-10 text-accent-2" />
                            <h3 class="mt-3 text-2xl font-bold">{{ $item['title'] }}</h3>
                            <p class="mt-1.5 text-muted">
                                {{ $item['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            <img class="w-auto h-full max-w-lg mx-auto" src="/assets/mobile-application/mobile.png" alt="Mobile Application">
        </div>
    </section>
</main>
@endsection
