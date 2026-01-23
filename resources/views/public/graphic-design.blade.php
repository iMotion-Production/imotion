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

    $choseUs = [
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

    $galleries = [
        [
            'src' => '/assets/graphic-design/portfolio/1.jpg',
            'alt' => 'Branding Visual Identity Design Zahab Ar Rahnu',
            'link' => 'https://www.behance.net/gallery/200827627/Branding-Visual-Identity-Design-Zahab-Ar-Rahnu',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/2.jpg',
            'alt' => 'Seo Optimization Olympia Education',
            'link' => 'https://www.behance.net/gallery/238148017/SEO-OPTIMIZATION-OLYMPIA-EDUCATION',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/3.jpg',
            'alt' => 'Website Design Development For Donnarica',
            'link' => 'https://www.behance.net/gallery/204906291/Website-Design-Development-for-Donnarica',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/4.jpg',
            'alt' => 'Visual Identity Design',
            'link' => 'https://www.behance.net/gallery/215864643/Visual-Identity-Design',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/5.jpg',
            'alt' => 'Company Profile Design Ocsb',
            'link' => 'https://www.behance.net/gallery/214511041/Company-Profile-Design-OCSB',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/6.jpg',
            'alt' => 'Packaging Design Kaikookies Sdn Bhd',
            'link' => 'https://www.behance.net/gallery/216694855/Packaging-Design-Kaikookies-Sdn-Bhd',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/7.jpg',
            'alt' => 'Corporate Magazine Design',
            'link' => 'https://www.behance.net/gallery/188773171/Corporate-Magazine-Design',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/8.jpg',
            'alt' => 'Financial Folder Design',
            'link' => 'https://www.behance.net/gallery/188956791/Financial-Folder-Design',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/9.jpg',
            'alt' => 'Social Media Design Secret Aglow',
            'link' => 'https://www.behance.net/gallery/214326115/Social-Media-Design-Secret-Aglow',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/10.jpg',
            'alt' => 'Visual Identity First Industrial Sdn Bhd',
            'link' => 'https://www.behance.net/gallery/212868941/Visual-Identity-First-Industrial-Sdn-Bhd',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/11.jpg',
            'alt' => 'Visual Identity Design UDML',
            'link' => 'https://www.behance.net/gallery/214493601/Visual-Identity-Design-UDLM',
        ],
        [
            'src' => '/assets/graphic-design/portfolio/12.jpg',
            'alt' => 'Book Design Islam Nature',
            'link' => 'https://www.behance.net/gallery/212685191/Book-Design-Islam-Nature',
        ],
    ];

@endphp

@section('content')
<main class="bg-background overflow-hidden">
    <x-breadcrumb title="Graphic Design" />
    <section data-aos="fade-up" class="container pt-5 sm:pt-7 lg:pt-12">
        <div class="text-center text-foreground max-w-3xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                The Art & Strategy of Exceptional Graphic Design
            </h1>
            <p class="mt-1.5 sm:mt-3 text-muted max-w-2xl mx-auto">
                Our Graphic Design services are crafted for startups, entrepreneurs, and corporate leaders who need more than just visuals — they need strategic design that strengthens their brand, captures attention, and converts audiences.
            </p>
        </div>
        <img src="/assets/graphic-design/base.png" alt="Graphic Design" class="w-full h-full object-cover max-w-5xl mx-auto mt-2">
    </section>
    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <div data-aos="fade-up" class="text-center max-w-4xl mx-auto">
            <h1 class="text-[2rem] md:text-[2.5rem] leading-small font-extrabold mt-2 text-foreground">
                Dedicated Remote Graphic Designer
            </h1>
            <p class="mt-3 text-base text-muted max-w-3xl leading-relaxed mx-auto">
                Collaborating with freelancers can be unpredictable, often resulting in inconsistent and questionable outcomes.
            </p>
        </div>
        <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
            @foreach($plans as $idx => $plan)
                <div data-aos="fade-up" data-aos-delay="{{ ($idx + 2) * 150 }}" class="bg-white border border-border text-slate-800 rounded-2xl flex {{ $plan['isPopular'] ?? false ? 'shadow-2xl shadow-lime-500/20' : '' }}">
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
                            <a href="https://whatsapp.imotion.my/creativedigitalmarketing" target="_blank" rel="noopener noreferrer" class="block w-full text-center mt-6 py-3 px-6 font-semibold text-slate-800 {{ $plan['isPopular'] ?? false ? 'bg-basic hover:bg-basic-hover' : 'bg-popular hover:bg-popular-hover' }} rounded-lg transition-colors duration-300">
                                Choose Plan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <section class="container pt-12 sm:pt-16 lg:pt-28">
        <x-title data-aos="fade-right" class="max-w-6xl" badge="Why Choose Us" heading="From agile startups to established enterprises, we craft bespoke creative journeys that reflect your unique ambitions" description="Don't just take our word for it — explore our portfolio  to see how we've helped brands like yours stand out with stunning, results-driven design." />
        <div class="relative z-10 mt-10 lg:mt-14 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <img data-aos="fade-right" class="w-full h-auto max-w-2xl" src="/assets/graphic-design/chose-us.png" alt="Graphic Design">
            <div data-aos="fade-left" class="space-y-2 sm:space-y-4">
                <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                    Our end-to-end design partnership adapts to your needs, empowering businesses at every stage with artistry, strategy, and unparalleled attention to detail.
                </h2>
                <p class="text-muted">
                    Our Business harmonize visionary creativity with strategic intelligence to craft graphic design that doesn't just inspire — it achieves.
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
    </section>
    <section class="container py-10 sm:py-14 lg:py-24">
        <x-title data-aos="fade-up" class="max-w-5xl" isCenter badge="Our Portfolio" heading="Let's See Our Best Artwork" description="We Impress your organization & customers with customization options, and enjoy stunning customer." />
        <div class="mt-8 lg:mt-12 flex flex-col gap-5 sm:gap-10">
            @foreach ($galleries as $gallery)
                <a
                    data-aos="fade-up"
                    href="{{ $gallery['link'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="block"
                >
                    <img
                        src="{{ $gallery['src'] }}"
                        alt="{{ $gallery['alt'] }}"
                        class="w-full h-auto mx-auto max-w-6xl rounded-xl shadow-lg"
                    />
                </a>
            @endforeach
        </div>
    </section>
</main>
@endsection
