@extends('public/layouts/base')

@php
    $contacts = [
        [
            'title' => 'Address',
            'icon' => 'ri-map-pin-line',
            'content' => '<a href="https://maps.app.goo.gl/yqkTXCtVCVUt9A7K7" target="_blank" rel="noopener noreferrer" class="underline decoration-2 decoration-accent-1 underline-offset-4">Level 4, Wisma Suria Jalan Teknokrat 6, Cyber 5 Cyberjaya, 63000 Sepang, Selangor, Malaysia</a>'
        ],
        [
            'title' => 'Telephone',
            'icon' => 'ri-phone-line',
            'content' => '<a href="tel:+60356144615" class="underline decoration-2 decoration-accent-1 underline-offset-4">+60 3 5614 4615</a>'
        ],
        [
            'title' => 'Email',
            'icon' => 'ri-mail-line',
            'content' => '<a href="mailto:helloimotion.pro@gmail.com" class="underline decoration-2 decoration-accent-1 underline-offset-4">helloimotion.pro@gmail.com</a>'
        ],
        [
            'title' => 'WhatsApp',
            'icon' => 'ri-whatsapp-line',
            'content' => '<a href="https://whatsapp.imotion.my/creativedigitalmarketing" target="_blank" rel="noopener noreferrer" class="underline decoration-2 decoration-accent-1 underline-offset-4">Hello iMotion</a>'
        ],
        [
            'title' => 'Telegram',
            'icon' => 'ri-telegram-2-line',
            'content' => '<img src="/assets/contact/telegram.jpg" alt="Telegram Banner" class="w-full shadow-2xl shadow-muted/15 h-52 mt-3 aspect-square overflow-hidden rounded-4xl">'
        ],
    ];
@endphp

@section('content')

<main class="bg-background overflow-hidden">
    <x-breadcrumb title="Contact" />
    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center mx-auto">
            <div x-data="{ isShow: false }" data-aos="fade-right" x-intersect.once="isShow = true" class="relative w-full h-full rounded-tl-3xl aspect-square rounded-br-3xl overflow-hidden mx-auto bg-foreground/5 rounded-lg shadow-xl min-h-[30rem] flex items-center justify-center">
                <template x-if="!isShow">
                    <div class="text-muted font-bold">Loading...</div>
                </template>
                <template x-if="isShow">
                    <iframe
                        class="w-full h-full aspect-square border-0"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.654333476605!2d101.65189417438418!3d2.91541569706097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4c0075e78e3b%3A0xd5583e5157e93403!2sImotion%20Production!5e0!3m2!1sid!2sid!4v1712376514197!5m2!1sid!2sid">
                    </iframe>
                </template>
            </div>
            <div data-aos="fade-left" class="lg:order-1 w-full max-w-xl mx-auto space-y-6">
                <div class="space-y-4">
                    <h2 class="text-3xl md:text-4xl font-bold text-foreground">Let us know</h2>
                    <p class="mt-3 text-base text-muted max-w-2xl">
                        iMotion Production is Malaysia's #1 unlimited graphic design service, Website Design & Development, Digital Marketing Solution. We offer services you'll love, at a fraction of the price.
                    </p>
                </div>
                <form class="space-y-4">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-muted">Name</label>
                        <input required type="text" id="name" name="name" class="w-full bg-white dark:bg-slate-900 border border-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-accent-1" required>
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-muted">Email</label>
                        <input required type="email" id="email" name="email" class="w-full bg-white dark:bg-slate-900 border border-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-accent-1" required>
                    </div>
                    <div class="space-y-2">
                        <label for="subject" class="block text-sm font-medium text-muted">Subject</label>
                        <input required type="text" id="subject" name="subject" class="w-full bg-white dark:bg-slate-900 border border-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-accent-1" required>
                    </div>
                    <div class="space-y-2">
                        <label for="message" class="block text-sm font-medium text-muted">Message</label>
                        <textarea required id="message" name="message" rows="5" class="w-full bg-white dark:bg-slate-900 border border-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-accent-1 min-h-16 max-h-52" required></textarea>
                    </div>
                    <a href="mailto:connect@imotion.my" class="flex items-center justify-center w-full gap-2 py-3 px-6 font-bold cursor-pointer text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>Send Message</span>
                        <x-icon name="ri-send-plane-line" class="size-5" />
                    </a>
                </form>
            </div>
        </div>
    </section>

    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @foreach ($contacts as $contact)
                <div class="flex items-start gap-4 {{ $loop->index === 0 ? 'lg:row-span-2' : '' }}">
                    <x-icon name="{{ $contact['icon'] }}" class="size-8 text-accent-2 flex-shrink-0" />
                    <div>
                        <h3 class="text-xl font-bold text-foreground">{{ $contact['title'] }}</h3>
                        <p class="mt-1 text-muted">{!! $contact['content'] !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
