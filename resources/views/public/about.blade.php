@extends('public/layouts/base')

@php
    $aboutUs = [
        'paragraph' => [
            'Specializing in Graphic Design, Digital Marketing, SEO Management, Social Media Design, and Video Animation & 3D, we empower startups, entrepreneurs, and corporate leaders to communicate with impact.',
            'Whether you\'re launching a new brand, promoting an event, or subtly your existing marketing materials, we are driven by our passion to move your business forward - creatively and strategically.',
        ],
        'list' => [
            'Value for Money',
            'After Sales support',
            'Clients Focused'
        ]
    ];

    $accordionItems = [
        [
            'title' => 'What is social media marketing?',
            'text' => 'Social media marketing (SMM) (also known as digital marketing and e-marketing) is the use of social media—the platforms on which users build social networks and share information—to build a company\'s brand, increase sales, and drive website traffic. In addition to providing companies with a way to engage with existing customers and reach new ones, social media marketing (SMM) has purpose-built data analytics that allow marketers to track the success of their efforts and identify even more ways to engage.'
        ],
        [
            'title' => 'Do you need to use social media marketing?',
            'text' => 'Social media marketing (SMM) (also known as digital marketing and e-marketing) is the use of social media—the platforms on which users build social networks and share information—to build a company\'s brand, increase sales, and drive website traffic. In addition to providing companies with a way to engage with existing customers and reach new ones, social media marketing (SMM) has purpose-built data analytics that allow marketers to track the success of their efforts and identify even more ways to engage.'
        ],
        [
            'title' => 'How do I join Printing On Demand Program?',
            'text' => 'Social media marketing (SMM) (also known as digital marketing and e-marketing) is the use of social media—the platforms on which users build social networks and share information—to build a company\'s brand, increase sales, and drive website traffic. In addition to providing companies with a way to engage with existing customers and reach new ones, social media marketing (SMM) has purpose-built data analytics that allow marketers to track the success of their efforts and identify even more ways to engage.'
        ],
        [
            'title' => 'How much does it cost to develop a custom mobile app?',
            'text' => 'Social media marketing (SMM) (also known as digital marketing and e-marketing) is the use of social media—the platforms on which users build social networks and share information—to build a company\'s brand, increase sales, and drive website traffic. In addition to providing companies with a way to engage with existing customers and reach new ones, social media marketing (SMM) has purpose-built data analytics that allow marketers to track the success of their efforts and identify even more ways to engage.'
        ]
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <x-breadcrumb title="About Us" />

    <section class="container pt-5 sm:pt-7 lg:pt-12">
        <div class="flex flex-col lg:flex-row gap-16 items-center justify-evenly">
            <img class="w-full h-auto max-w-xl" src="/assets/home/about.png" alt="Illustration of Designed to Drive Results">
            <div class="text-left text-foreground max-w-2xl">
                <x-title badge="About Us" heading="Designed to Drive Results" />
                <div class="mt-2 sm:mt-4 space-y-3 sm:space-y-4 text-muted">
                    @foreach ($aboutUs['paragraph'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
                <ul class="mt-2 sm:mt-4 space-y-2 sm:space-y-3 text-muted">
                    @foreach ($aboutUs['list'] as $item)
                        <li class="flex items-start gap-3">
                            <span class="text-accent-2">
                                <x-icon name="ri-check-line" class="size-5" />
                            </span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3 sm:mt-6">
                    <a href="#" class="inline-flex items-center justify-center gap-2 py-3 px-6 font-semibold text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>View More</span>
                        <x-icon name="ri-arrow-right-line" class="size-5" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <x-title isCenter badge="FAQ" heading="Frequently Asked Question." description="Go behind the scenes and discover the processes, strategies and insights that drive our creative, culture, and ultimately, our client's success" />
        <div class="mt-10 lg:mt-14 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="relative w-full max-w-xl aspect-[4/3] rounded-tl-4xl rounded-br-4xl overflow-hidden mx-auto lg:order-2">
                <img src="/assets/home/faq.jpg" alt="FAQ Placeholder" class="w-full h-full object-cover">
            </div>
            <div x-data="{ selected: '0' }" class="lg:order-1 w-full space-y-4 text-foreground">
                @foreach ($accordionItems as $item)
                    <div class="border border-accent-1/30 rounded-tl-3xl rounded-br-3xl overflow-hidden">
                        <button id="accordion-control-{{ $loop->index }}"
                                type="button"
                                class="flex w-full items-center justify-between gap-4 bg-accent-1/5 dark:bg-accent-1/10 px-4 sm:px-6 py-4 text-left underline-offset-2 hover:bg-accent-1/10 focus-visible:bg-accent-1/10 dark:hover:bg-accent-1/15 dark:focus-visible:bg-accent-1/15 focus-visible:underline focus-visible:outline-hidden transition-colors duration-300 cursor-pointer"
                                aria-controls="accordion-{{ $loop->index }}"
                                x-on:click="selected = '{{ $loop->index }}'"
                                x-bind:class="selected === '{{ $loop->index }}' ? 'font-bold': 'font-medium'"
                                x-bind:aria-expanded="selected === '{{ $loop->index }}' ? 'true' : 'false'">
                            <span>{{ $item['title'] }}</span>
                            <x-icon name="ri-arrow-down-s-line" class="size-5 shrink-0 transition-transform duration-300" aria-hidden="true" x-bind:class="selected === '{{ $loop->index }}'  ?  'rotate-180'  :  ''" />
                        </button>

                        <div x-cloak x-show="selected === '{{ $loop->index }}'" id="accordion-{{ $loop->index }}" role="region" aria-labelledby="accordion-control-{{ $loop->index }}" x-collapse>
                            <div class="p-4 sm:p-6 text-base text-muted text-pretty">
                                {{ $item['text'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="relative w-full max-w-xl aspect-[4/3] rounded-tl-4xl rounded-br-4xl overflow-hidden mx-auto">
                <img src="/assets/home/project.jpg" alt="Project Kick-Off" class="w-full h-full object-cover">
            </div>
            <div class="space-y-6">
                <x-title class="max-w-4xl" badge="Project Kick-Off" heading="Start Your Project" description="This is the space we cherish, where we either ideate or imagine and find our way to anyone with the desire to digitalize their kingdom and thrive. We delve into and find out a way for a user to achieve their quantum growth goals." />
                <form class="mt-3 sm:mt-6 flex items-center w-full relative max-w-xl">
                    <input type="email" placeholder="Enter your email..." class="w-full rounded-l-lg border-foreground/30 bg-transparent p-4 pr-32 text-sm text-foreground placeholder:text-foreground/60 focus:border-accent-1 focus:ring-0">
                    <button type="submit" class="bg-accent-1 px-5 py-3.5 border border-accent-1 rounded-r-lg font-semibold text-slate-900 hover:bg-accent-1-hover transition-colors">
                        <x-icon name="ri-send-plane-fill" class="size-6" />
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
