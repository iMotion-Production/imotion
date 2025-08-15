@extends('public/layouts/base')

@php
    $plans = [
        [
            'name' => 'Basic',
            'desc' => 'Professional Quality & Economic Budget',
            'features' => [
                '1 Dedicated Designer',
                'Digital Ads & Social Media',
                'Print, Packaging & Merch',
                'Branding Design',
                'Presentation Design',
                '1 task at 1 time',
                'Real-time Project in Progress Preview',
            ],
            'price' => 1250,
        ],
        [
            'name' => 'Creative Pack',
            'desc' => 'Exclusive, Premium and Quality Maximize Workload',
            'isPopular' => true,
            'features' => [
                '3 Dedicated Designer',
                'Digital Ads & Social Media',
                'Illustration, Print, Packaging & Merch',
                'Branding Design, Website Design',
                'Motion Graphics / Videos Design',
                'Real-time Project in Progress Preview',
                'AI Capabilities',
            ],
            'price' => 3250,
        ],
        [
            'name' => 'Business Pack',
            'desc' => 'Exclusive, Premium Quality & Commercial Design',
            'features' => [
                '5 Dedicated Designer',
                'Digital Ads & Social Media',
                'Illustration, Print, Packaging & Merch',
                'Branding Design, Website Design',
                '3D & Motion Graphics / Videos Design',
                'Real-time Project in Progress Preview',
                'AI Capabilities',
            ],
            'price' => 5800,
        ],
    ];

    $whyChoseUs = [
        [
            'title' => 'Builds instant brand recognition',
            'description' => 'Polished, custom design signals trust and authority, helping you stand out as a serious player in your industry.',
        ],
        [
            'title' => 'Elevates credibility and professionalism',
            'description' => 'Exquisite, custom-crafted design that conveys sophistication and authority, positioning your brand as a trusted leader in your industry.',
        ],
        [
            'title' => 'Aligns with marketing, PR, and corporate communication strategy ',
            'description' => 'Strategic design that aligns with your campaigns, messaging, and business objectives — from product launches to investor reports.',
        ],
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <x-breadcrumb title="Graphic Design" />
    <section class="container pb-8 sm:pb-12 lg:pb-20 pt-5 sm:pt-7 lg:pt-12">
        <div class="text-center text-foreground max-w-5xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                The Art & Strategy of Exceptional Graphic Design
            </h1>
            <p class="mt-1.5 sm:mt-3 text-base sm:text-lg text-muted max-w-4xl mx-auto">
                Our Graphic Design services are crafted for startups, entrepreneurs, and corporate leaders who need more than just visuals — they need strategic design that strengthens their brand, captures attention, and converts audiences.
            </p>
        </div>
        <img src="/assets/graphic-design/base.png" alt="Graphic Design" class="w-full h-full object-cover max-w-5xl mx-auto mt-2">
    </section>
    <section class="py-16 lg:py-24">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold">
                    Dedicated Remote Graphic Designer
                </h1>
                <p class="mt-4 text-lg text-gray-400">
                    Collaborating with freelancers can be unpredictable, often resulting in inconsistent and questionable outcomes.
                </p>
            </div>
            <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                @foreach($plans as $plan)
                    <div class="bg-white border border-border text-slate-800 rounded-2xl flex {{ $plan['isPopular'] ?? false ? 'shadow-2xl shadow-lime-500/20' : '' }}">
                        <div class="mx-auto max-w-md w-full p-8 flex flex-col">
                            <div class="flex items-center">
                                <span class="inline-block {{ $plan['isPopular'] ?? false ? 'bg-basic' : 'bg-popular' }} text-sm font-semibold py-2 px-5 rounded-full">{{ $plan['name'] }}</span>
                                @if($plan['isPopular'] ?? false)
                                    <span class="text-xs py-1.5 px-3 font-bold -ml-3 bg-slate-100 rounded-full">Most Popular Plan</span>
                                @endisset
                            </div>
                            <p class="mt-6 font-semibold">{{ $plan['desc'] }}</p>
                            <ul class="my-8 space-y-4 text-slate-600">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-center gap-3">
                                        <div class="size-5 rounded-full {{ $plan['isPopular'] ?? false ? 'bg-basic' : 'bg-popular' }} text-slate-800 flex-shrink-0 flex items-center justify-center">
                                        <x-icon name="ri-check-line" class="size-4" />
                                        </div>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-auto">
                                <p class="text-slate-800 text-center">
                                    <span class="text-4xl font-bold">RM {{ $plan['price'] }}</span>
                                    <span class="text-base font-medium text-slate-500">/monthly</span>
                                </p>
                                <a href="#" class="block w-full text-center mt-6 py-3 px-6 font-semibold text-slate-800 {{ $plan['isPopular'] ?? false ? 'bg-basic hover:bg-basic-hover' : 'bg-popular hover:bg-popular-hover' }} rounded-lg transition-colors duration-300">
                                    Choose Plan
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="container pb-8 sm:pb-12 lg:pb-20 pt-5 sm:pt-7 lg:pt-12">
        <div class="text-left text-foreground max-w-7xl">
            <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                Why Choose Us
            </span>
            <h1 class="text-4xl md:text-5xl font-bold mt-3">
                From agile startups to established enterprises, we craft bespoke creative journeys that reflect your unique ambitions
            </h1>
            <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-5xl">
                Don't just take our word for it — explore our portfolio  to see how we've helped brands like yours stand out with stunning, results-driven design.
            </p>
        </div>
        <div class="relative z-10 mt-12 lg:mt-16 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <img class="w-full h-auto max-w-2xl" src="/assets/graphic-design/chose-us.png" alt="Graphic Design">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                    Our end-to-end design partnership adapts to your needs, empowering businesses at every stage with artistry, strategy, and unparalleled attention to detail.
                </h2>
                <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-3xl">
                    Our Business harmonize visionary creativity with strategic intelligence to craft graphic design that doesn't just inspire — it achieves.
                </p>
                <div class="space-y-4 pt-4">
                    @foreach ($whyChoseUs as $item)
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
    <section class="container pb-10 sm:pb-14 lg:pb-24">
        <div class="text-left text-foreground max-w-6xl mx-auto">
            <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                Our Portfolio
            </span>
            <h1 class="text-4xl md:text-5xl font-bold mt-3">
                Let's See Our Best Artwork
            </h1>
            <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-2xl">
                We Impress your organization & customers with customization options, and enjoy stunning customer.
            </p>
        </div>
        <div class="mt-8 lg:mt-12 flex flex-col gap-6 sm:gap-10">
            @for ($i = 1; $i <= 12; $i++)
                <img
                    src="/assets/graphic-design/portfolio/{{ $i }}.jpg"
                    alt="Portfolio {{ $i }}"
                    class="w-full h-auto mx-auto max-w-6xl rounded-xl shadow-lg"
                />
            @endfor
        </div>
    </section>
</main>
@endsection
