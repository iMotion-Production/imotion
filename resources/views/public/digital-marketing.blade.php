@extends('public/layouts/base')

@php
    $items = [
        [
            'title' => 'Frequent Content Consulting',
            'description' => 'We\'ll guide as you implement our strategy, walking you through every step.',
        ],
        [
            'title' => 'One-Time Content Setup',
            'description' => 'We\'ll create a thorough list of content marketing improvements you can act on.',
        ],
        [
            'title' => 'Pure Content Performance',
            'description' => 'Revenue share content partnership that pays us more for additional performance.',
        ],
    ];

    $skillSets = [
        [
            'icon' => 'ri-pencil-ruler-2-line',
            'title' => 'Product Design',
            'description' => 'Experienced on how to understand users, solve problems and build great products.',
        ],
        [
            'icon' => 'ri-pen-nib-line',
            'title' => 'Visual Design',
            'description' => 'Our Expertise to develop the eye for design. Colors, typography, layout and the whole package.',
        ],
        [
            'icon' => 'ri-movie-line',
            'title' => 'Motion Design',
            'description' => 'Motion is something that really fascinates me because of the flexibility of story telling.',
        ],
        [
            'icon' => 'ri-camera-lens-line',
            'title' => 'Photography',
            'description' => 'Clicking pictures really brings out the creative in branding. Photography really makes you look and perceive things in a different way.',
        ],
    ];

    $pricingPlans = [
        [
            'name' => 'Standard',
            'price' => 'RM 499.00',
            'features' => [
                'Content Plan & Creation',
                '4 Content Post Every Week',
                'Social Page Creation & Optimization',
                'Audit & Strategy',
                'Monthly Report & Recommendation',
            ],
            'highlight' => false,
        ],
        [
            'name' => 'Essentials',
            'price' => 'RM 999.00',
            'features' => [
                'Social Page Creation & Optimization',
                'Content Plan & Creation',
                'Campaign Optimisation',
                'Audit & Strategy',
                'Monthly Report & Recommendation',
            ],
            'highlight' => true,
        ],
        [
            'name' => 'Premium',
            'price' => 'RM 1500.00',
            'features' => [
                'Social Page Creation & Optimization',
                'Audit & Strategy',
                'Content Plan & Creation',
                'Sale & ROAS Result Focus',
                'Monthly Report & Recommendation',
            ],
            'highlight' => false,
        ],
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <x-breadcrumb title="Digital Marketing" />

    <section class="container pt-5 sm:pt-7 lg:pt-12">
        <div class="text-center text-foreground max-w-2xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                The Content We Throw Away Is Better Than What Most People Publish
            </h1>
            <p class="mt-1.5 sm:mt-3 text-muted max-w-xl mx-auto">
                Deep, compelling, data-driven content that drives more value for readers and higher conversion rates for you.
            </p>
        </div>

        <div class="mt-10 lg:mt-14 grid grid-cols-1 xl:grid-cols-2 gap-12 xl:gap-16 items-center">
            <div class="relative w-full max-w-xl mx-auto">
                <img src="/assets/digital-marketing/toolbar.png" alt="Toolbar Digital Marketing" class="h-auto w-10 xs:w-14 absolute -top-6 xs:-top-10 md:-top-14 left-0">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 ml-10 xs:ml-14 lg:ml-16">
                    @foreach(range(1, 9) as $idx)
                        <img src="/assets/digital-marketing/grid/{{ $idx }}.jpg" alt="Portfolio Image {{ $idx }}" class="w-full aspect-square object-cover rounded-lg {{ $idx == 9 ? 'hidden sm:block' : '' }}">
                    @endforeach
                </div>
            </div>
            <div class="space-y-2 sm:space-y-4 max-w-2xl mx-auto">
                <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                    Choose The Partnership That's Best For You, Start Small or Go Big
                </h2>
                <p class="text-muted">
                    Pick from a variety of content marketing options that work for your budget and speed. We'll handle all your content marketing and report the performance directly to you.
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
        <x-title class="max-w-5xl" isCenter badge="Our Expertise" heading="We run Your brand campaign and let you focus on what's important" />
        <img src="/assets/digital-marketing/tiktok.png" alt="Our Expertise Tiktok" class="w-full h-full object-cover max-w-7xl mx-auto mt-6 sm:mt-14">
        <div class="mt-8 sm:mt-14 lg:mt-20 grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 items-center max-w-5xl mx-auto">
            <div class="relative w-full max-w-md mx-auto text-center md:text-left">
                <h3 class="text-2xl sm:text-3xl font-bold">
                    STAMPS OF EXCELLENCE
                </h3>
                <p class="mt-1.5 sm:mt-3 text-muted">
                    We let our results and an impressive array of professional certifications and accolades do the talking and speak volumes about our expertise and proficiency in the industry.
                </p>
            </div>
            <div class="max-w-sm mx-auto">
                <img src="/assets/digital-marketing/partner.png" alt="Our Partner" class="w-full h-auto object-cover invert-100 dark:invert-0">
            </div>
        </div>
        <img src="/assets/digital-marketing/process.png" alt="Our Expertise Process" class="w-full h-full object-cover max-w-7xl mx-auto mt-6 sm:mt-14">
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 xs:gap-12 lg:gap-16 text-foreground">
            <div class="lg:col-span-1">
                <h2 class="text-3xl sm:text-4xl font-bold">Skillset</h2>
                <p class="mt-2 sm:mt-4 text-base sm:text-[18px] text-muted">
                    With skills in over 4 different fields of design, We the perfect person to hire when it comes to a full fledged project. Whatever your needs are, I can pretty much take on any challenge.
                </p>
            </div>
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6 xs:gap-8 sm:gap-10 lg:gap-14">
                @foreach ($skillSets as $skill)
                    <div>
                        <x-icon name="{{ $skill['icon'] }}" class="size-10 text-accent-2" />
                        <h3 class="mt-3 text-2xl font-bold">{{ $skill['title'] }}</h3>
                        <p class="mt-1.5 text-muted">
                            {{ $skill['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="container py-10 sm:py-14 lg:py-24">
        <x-title class="max-w-5xl" isCenter badge="Our Pricing Plan" heading="Pricing We're Offering" description="It is important to understand that the social media marketing pricing below is based on an estimate versus our current pricing. So, do take it as a guide." />
        <div class="grid gap-5 sm:gap-8 md:grid-cols-2 lg:grid-cols-3 mt-8 sm:mt-14 lg:mt-20">
            @foreach ($pricingPlans as $plan)
            <div class="p-3 border border-accent-2/20 rounded-tl-3xl rounded-br-3xl {{ $plan['highlight'] ? 'lg:scale-105 z-10 shadow-2xl shadow-accent-1/20' : '' }}">
                <div class="bg-background p-8 space-y-6 h-full flex flex-col max-w-[26rem] mx-auto">
                <h2 class="text-center text-3xl sm:text-4xl font-extrabold text-foreground">{{ $plan['name'] }}</h2>
                <p class="text-center font-bold">
                    <span class="text-xl sm:text-2xl md:text-3xl text-foreground">{{ $plan['price'] }}</span>
                    <span class="text-sm sm:text-base text-muted">/m</span>
                </p>
                <ul class="space-y-3 text-muted">
                    @foreach ($plan['features'] as $feature)
                    <li class="flex items-center gap-3">
                        <div class="size-5 rounded-full bg-accent-2 text-background flex-shrink-0 flex items-center justify-center">
                            <x-icon name="ri-check-line" class="size-4" />
                        </div>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="#" class="flex items-center justify-center gap-2 py-3 px-6 font-semibold text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                    <span>Get Started</span>
                    <x-icon name="ri-arrow-right-line" class="size-5" />
                </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
