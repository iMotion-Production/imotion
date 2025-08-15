@extends('public/layouts/base')

@php
    $services = [
        [
            'title' => 'Graphic Design',
            'icon' => 'ri-customer-service-2-fill',
            'description' => 'Craft a powerful brand identity with stunning visuals that communicate your story and stand out in any market.  ',
            'url' => '#'
        ],
        [
            'title' => 'Digital Marketing',
            'icon' => 'ri-government-fill',
            'description' => 'Grow your online presence with strategic campaigns, engaging content, and proven SEO techniques that boost visibility and conversions.',
            'url' => '#'
        ],
        [
            'title' => 'Social Media Management',
            'icon' => 'ri-pushpin-2-fill',
            'description' => 'Strategic content creation, scheduling, and engagement to grow your online community.',
            'url' => '#'
        ],
        [
            'title' => 'Website Design & Development',
            'icon' => 'ri-pen-nib-line',
            'description' => 'Build a fast, responsive, and visually appealing website that reflects your brand and business goals.',
            'url' => '#'
        ],
        [
            'title' => 'Video Advertising',
            'icon' => 'ri-play-circle-line',
            'description' => 'Capture audiences with high-impact video ads, animated explainers, and immersive stories for digital and social media.',
            'url' => '#'
        ],
        [
            'title' => 'Mobile App Design & Developments',
            'icon' => 'ri-smartphone-line',
            'description' => 'Create intuitive, user-friendly mobile apps that deliver seamless experiences across iOS and Android platforms.',
            'url' => '#'
        ]
    ];

    $choseUs = [
        [
            'title' => '24/7 Help desk Availability',
            'description' => 'We provide our clients with direct access to a dedicated team.',
        ],
        [
            'title' => 'Risk-Free Satisfaction',
            'description' => 'We pour our combined knowledge and experience into every project.',
        ],
        [
            'title' => 'Fresh Business Thinking',
            'description' => 'Innovate your business strategy with fresh thinking that drives innovation.',
        ],
    ];

    $experiences = [
        [
            "value" => "90+",
            "label" => "Clients"
        ],
        [
            "value" => "400+",
            "label" => "Designs"
        ],
        [
            "value" => "500+",
            "label" => "Projects"
        ],
        [
            "value" => "30+",
            "label" => "Team Member"
        ]
    ];

    $portfolio = [
        'Advertising Services',
        'Printing Services',
        'Web Development',
        'App Development',
        'Digital Marketing',
    ];

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

    $blogs = [
        [
            'title' => 'The Ultimate Guide to Marketing Strategies & How to Improve Your Digital Presence',
            'image' => '1.jpg',
            'date' => '2023-04-01 01:52:00',
            'url' => '#'
        ],
        [
            'title' => 'The Ultimate Guide to Marketing Strategies',
            'image' => '2.jpg',
            'date' => '2023-04-01 01:52:00',
            'url' => '#'
        ],
        [
            'title' => 'UX Strategy: What Is It & Why It Matters',
            'image' => '3.jpg',
            'date' => '2023-04-01 01:52:00',
            'url' => '#'
        ],
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="flex flex-col-reverse lg:flex-row gap-16 items-center justify-evenly">
            <div class="text-center lg:text-left text-foreground max-w-3xl">
                <x-badge title="FutureProof Digital Marketing Agency" />
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold uppercase mt-3">
                    iMotion Production Creative Design & Digital Marketing Agency
                </h1>
                <p class="mt-3 sm:mt-6 text-muted max-w-xl mx-auto lg:mx-0">
                    From digital strategy, specialize in SEO - We Craft Digital Experiences That Engage, Convert, and Grow Your Business.
                </p>
                <div class="mt-4 sm:mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-2 sm:gap-4">
                    <a href="#" class="flex items-center justify-center gap-2 py-3 px-6 font-semibold text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>Get Started</span>
                        <x-icon name="ri-arrow-right-line" class="size-5" />
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 py-3 px-6 border border-accent-2 font-semibold text-foreground bg-transparent-1 hover:bg-accent-2 hover:text-background rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>Contact Us</span>
                        <x-icon name="ri-messenger-line" class="size-5" />
                    </a>
                </div>
            </div>
            <img class="w-full h-auto max-w-lg" src="/assets/home/hero.png" alt="Illustration of FutureProof Digital Marketing Agency">
        </div>
    </section>

    <section class="container">
        <h2 class="text-2xl sm:text-3xl text-center font-bold text-foreground">They trust Us</h2>
        <div class="mt-8 sm:mt-12 flex items-center justify-center gap-6 sm:gap-10 flex-wrap">
            @for ($index = 1; $index <= 34; $index++)
                <img src="/assets/home/logo/{{ $index }}.png" loading="lazy" alt="Logo {{ $index }}" class="h-16 w-auto invert-100 dark:invert-0">
            @endfor
        </div>
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
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
        <x-title isCenter badge="What We Do" heading="Our Creative & Digital Marketing Services" description="We are a powerhouse of dedicated experts and creators in all modern fields of Digital Marketing. We empower your business using the most effective tools in combination with proven strategies, creative magic and data-driven assets, we love the leeway to take your business to the next level and beyond." />
         <div class="mt-10 lg:mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @foreach ($services as $service)
                <div class="flex flex-col items-center bg-background border border-border rounded-2xl p-8 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="w-16 h-16 rounded-full bg-accent-2/10 dark:bg-accent-2/20 text-accent-2 mx-auto flex items-center justify-center">
                        <x-icon name="{{ $service['icon'] }}" class="size-8" />
                    </div>
                    <div class="space-y-2 mt-6 flex-1">
                        <h3 class="text-xl font-bold text-foreground">{{ $service['title'] }}</h3>
                        <p class="mt-2 text-muted text-sm leading-relaxed">
                            {{ $service['description'] }}
                        </p>
                    </div>
                    <a href="{{ $service['url'] }}" class="inline-flex items-center gap-1.5 mt-6 text-sm font-semibold text-muted hover:text-accent-1 transition-colors">
                        <span>Read More</span>
                        <x-icon name="ri-arrow-right-line" class="size-4" />
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section x-data="videoModal()" class="relative pt-12 sm:pt-16 lg:pt-28">
        <div class="container">
            <x-title class="max-w-4xl" badge="Why Choose Us" heading="Our Digital Agency Delivers Extraordinary Business Value" description="Our continuous automation of receptive processes and use of intelligent tools in product development enable us to deliver within a short timeframe and increase the time to value ratio." />
            <div class="relative z-10 mt-10 lg:mt-14 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="relative w-full max-w-md aspect-square rounded-tl-4xl rounded-br-4xl overflow-hidden border border-border mx-auto">
                    <img src="/assets/home/video-thumb.jpg" alt="Video Placeholder" class="w-full h-full object-cover">
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                        <button @click="openModal()" class="p-4 rounded-full bg-background/50 text-accent-2 hover:bg-accent-2 hover:text-background backdrop-blur-sm border border-border transition-colors duration-300 cursor-pointer">
                            <x-icon name="ri-play-fill" class="size-10 text-current" />
                        </button>
                    </div>
                </div>

                <div class="space-y-2 sm:space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                        Proactively - with a transparent process to uphold both your deadlines & your budget
                    </h2>
                    <p class="text-muted">
                        Our approach to steering digital products and services is grounded in strategy, design, data and innovation. We work in close-knit, efficient teams that allow us to be nimble when tackling some of the biggest and most complex business challenges, defining culture and creating brand desire.
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

        <img src="/assets/home/decorator-mobile.png" alt="Decorator mobile" aria-hidden="true" class="absolute -top-10 -right-5 w-[520px] pointer-events-none z-0 hidden xl:block">

        <div x-cloak x-show="popupOpen" @keydown.escape.window="closeModal()" class="fixed inset-0 z-50 flex items-center justify-center bg-background/80 backdrop-blur-sm p-4" aria-modal="true">
            <div x-show="popupOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" @click.away="closeModal()" class="relative w-full max-w-4xl bg-background rounded-xl shadow-2xl p-3 sm:p-5">
                <div class="aspect-video">
                    <video x-ref="videoPlayer" controls class="w-full h-full">
                        <source src="/assets/home/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <button @click="closeModal()" aria-label="Close" class="absolute -top-10 sm:-top-6 lg:-top-4 right-0 lg:-right-4 size-10 bg-accent-2 rounded-full text-background flex items-center justify-center transition-colors cursor-pointer hover:bg-accent-2-hover">
                    <x-icon name="ri-close-line" class="size-6" />
                </button>
            </div>
        </div>
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <x-title class="max-w-5xl" isCenter badge="Our Experience" heading="A Proven 10-Year History Of Success In Creative Digital Marketing Technology" />
        <div class="mt-10 lg:mt-14 grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 items-start">
            @foreach ($experiences as $idx => $experience)
                <div class="bg-foreground/3 dark:bg-foreground/10 border border-border p-8 rounded-lg text-center w-full {{ $idx % 2 == 0 ? 'md:mt-8' : '' }}">
                    <p class="text-3xl lg:text-4xl font-extrabold text-accent-2">{{ $experience['value'] }}</p>
                    <div class="mt-2 text-lg sm:text-xl text-foreground font-semibold">{{ $experience['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <div class="max-w-7xl mx-auto">
            <x-title badge="Our Portfolio" heading="Let's See Our Best Work" description="We produce sustainable creative for brands looking to produce high-quality content, showcase their product and communicate messages quickly, without all the fuss of traditional shoot production!" />
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-y-8 mt-10 lg:mt-14">
                @foreach ($portfolio as $idx => $value)
                    <div class="relative max-w-xl mx-auto flex items-center justify-center rounded-lg rounded-tl-4xl rounded-br-4xl overflow-hidden aspect-square {{ $idx === 0 ? 'lg:col-start-2' : '' }}">
                        <img src="/assets/home/portfolio/{{ $idx + 1 }}.jpg" alt="{{ $value }}" class="w-full h-full object-cover">
                        <x-badge class="absolute bottom-5" title="{{ $value }}" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <x-title isCenter badge="Our Team Member" heading="Meet Our Team" description="We thrive on the rush of taking on new challenges with daring and groundbreaking brands. With a fiery passion for innovation and disruption, we’re always eager to push the boundaries and deliver extraordinary results that exceed expectations." />
        <div class="flex flex-col items-center max-w-sm mx-auto mt-8">
            <div class="overflow-hidden rounded-full size-64 border border-border bg-foreground/50 relative">
                <img src="/assets/home/author.png" alt="Author 1">
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-foreground mt-4">Ahmad Nadzri</h3>
                <p class="text-muted text-sm">Founder</p>
            </div>
        </div>
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <x-title isCenter badge="Our Work Force" heading="Our Strategy" description="Our in-house team have the knowledge and experience to handle complex marketing and growth challenges enabling ambitious companies to improve their marketing and brand online." />
        <img src="/assets/home/strategy.png" alt="Our Strategy" class="w-full h-full object-cover max-w-6xl mx-auto">
    </section>

    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="relative w-full max-w-xl aspect-[4/3] rounded-tl-4xl rounded-br-4xl overflow-hidden mx-auto lg:order-2">
                <img src="/assets/home/project.jpg" alt="Project Kick-Off" class="w-full h-full object-cover">
            </div>
            <div class="space-y-6 lg:order-1">
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
        <x-title isCenter badge="Community News" heading="Latest News" description="Subscribe and stay up to date with the latest our tips and news." />
         <div class="mt-10 lg:mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @foreach ($blogs as $blog)
                <div class="flex flex-col max-w-lg mx-auto rounded-tl-4xl rounded-br-4xl overflow-hidden border border-border hover:shadow-lg transition-shadow duration-300">
                    <div class="w-full h-auto aspect-video">
                        <img src="/assets/home/blogs/{{ $blog['image'] }}" alt="{{ $blog['title'] }}" class=" w-full h-auto object-cover">
                    </div>
                    <div class="p-6 flex flex-col flex-1 gap-2 sm:gap-3">
                        <div class="text-muted text-sm flex items-center gap-1.5">
                            <x-icon name="ri-calendar-2-line" class="size-4" />
                            <span>{{ \Carbon\Carbon::parse($blog['date'])->format('F j, Y h:i A') }}</span>
                        </div>
                        <h3 class="flex-1 flex flex-col justify-center text-xl font-bold text-foreground">{{ $blog['title'] }}</h3>
                        <a href="{{ $blog['url'] }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-muted hover:text-accent-1 transition-colors">
                            <span>Read More</span>
                            <x-icon name="ri-arrow-right-line" class="size-4" />
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
