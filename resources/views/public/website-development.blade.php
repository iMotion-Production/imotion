@extends('public/layouts/base')

@php
    $choseUs = [
        [
            'title' => 'Creating Great Website Experiences',
            'description' => 'Our programming squad plays with cutting edge technology and We have deep insights into many industries and an expert UX/UI team ready to roll.',
        ],
        [
            'title' => 'Combining Quantitative Data With Qualitative Insights',
            'description' => 'Our campaign capabilities help brands enrich and amplify the way they communicate with the world around them.',
        ],
        [
            'title' => 'Ultra-Fast Expedited Service Available',
            'description' => 'We\'ve refined our process as a web design agency through designing & developing websites for years.',
        ],
    ];

    $projects = [
        [
            'category' => 'BEAUTY CARE',
            'title' => "Le'verseau Cartel",
            'image' => 'leversau-cartel.png',
            'description' => 'Teleportation is no longer science fiction. Simplifying your way to travel in and around cities without worrying about cab drivers that go the long way.',
        ],
        [
            'category' => 'CONSTRUCTION',
            'title' => 'Unirio Energy Sdn Bhd',
            'image' => 'uni10-energy.png',
            'description' => 'Unirio Energy Sdn Bhd (Unirio Energy) is a Bumiputera corporation with more than 10 years of experience serving diverse clientele, comprised of government-related organizations (GLCs) and established, multinational corporations through our end-to-end solutions throughout the country.',
        ],
        [
            'category' => 'ECOMMERCE',
            'title' => 'Pasarantara',
            'image' => 'pasarantara.png',
            'description' => 'Founded in 2022, Pasarantara is a creative-led brand. Areas wishing to call themselves artists work closely with us and channel their vision through our technology, logistics and payments solutions.',
        ],
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <x-breadcrumb title="Website Design & Development" />
    <section class="container pt-5 sm:pt-7 lg:pt-12">
        <div class="text-center text-foreground max-w-5xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                We provide you with a dedicated, full scale development team that is specific to your business goals and technical needs.
            </h1>
            <p class="mt-1.5 sm:mt-3 text-muted max-w-3xl mx-auto">
                We build brands, products and all the digital experiences in between. We take immense pride in what we do because we want our clients to excel.
            </p>
        </div>
        <img src="/assets/website-development/base.png" alt="Website Design & Development" class="w-full h-full object-cover max-w-5xl mx-auto mt-6">
    </section>
    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <div class="flex flex-col 2xl:flex-row gap-12">
            <div class="text-left text-foreground max-w-2xl">
                <x-title badge="Our Workflow" heading="Our UX/UI Design Process" />
                <div class="mt-2 sm:mt-4 space-y-3 sm:space-y-4 text-muted">
                    <p>Our goal is to discover the roadblocks affecting the overall user experience of your digital product. We strive to create an interface that aligns with your creative vision and answers your user's needs.</p>
                    <p>We follow a UX process that relies heavily on research, analytics, and user testing to build a fully functional final product. Here's a breakdown of the steps we take in the design process.</p>
                </div>
            </div>
            <img src="/assets/website-development/our-process.png" alt="Our Process Website Design & Development" class="w-full h-full object-cover max-w-5xl mx-auto mt-6 block dark:hidden">
            <img src="/assets/website-development/our-process-dark.png" alt="Our Process Website Design & Development" class="w-full h-full object-cover max-w-5xl mx-auto mt-6 hidden dark:block">
        </div>
    </section>
    <section class="relative">
        <div class="container pt-12 sm:pt-16 lg:pt-28">
            <x-title class="max-w-4xl" badge="Why Choose Us" heading="We Are Creative People with Big Dream" description="We produce awesome visuals. Full of energy, drive, and passion, our team aims to deliver the most outstanding work in every pixel." />
            <div class="relative z-10 mt-10 lg:mt-14 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <img class="w-full h-auto max-w-2xl" src="/assets/website-development/code.png" alt="Why Chose Us Website Development">
                <div class="space-y-2 sm:space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                        We pour our heart into every design
                    </h2>
                    <p class="text-muted">
                        Data We do what we do because we love it and are dedicated to creating meaningful work. That's why clients hire us. Our heart is set on their goals.
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
        <img src="/assets/website-development/why-chose-us.png" alt="Decorator bags" aria-hidden="true" class="absolute top-0 right-16 w-md pointer-events-none z-0 hidden xl:block">
    </section>
    <section class="container py-10 sm:py-14 lg:py-24">
        <x-title class="max-w-5xl" isCenter badge="Our Portfolio" heading="We changing the way brands connect with audiences in a digital world" description="Our team can create amazing web experiences, beginning with deep market research, practical strategies, and professional execution." />
        <div class="mt-10 lg:mt-14 flex flex-col gap-5 lg:gap-10">
            @foreach ($projects as $idx => $project)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="relative w-full max-w-xl mx-auto{{ $idx % 2 === 1 ? ' lg:order-2' : '' }}">
                    <img src="/assets/website-development/{{ $project['image'] }}" alt="{{ $project['title'] }} Preview" class="w-full h-full object-cover rounded-2xl">
                </div>
                <div class="lg:order-1">
                    <div class="text-base font-bold tracking-[.4rem] sm:tracking-[.8rem] text-accent-2">{{ $project['category'] }}</div>
                    <h2 class="mt-1 sm:mt-1 text-4xl sm:text-5xl font-extrabold text-foreground">
                        {{ $project['title'] }}
                    </h2>
                    <p class="mt-2 sm:mt-4 text-muted max-w-2xl">
                        {{ $project['description'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
