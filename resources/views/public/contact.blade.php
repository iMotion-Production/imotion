@extends('public/layouts/base')

@php
    $contacts = [
        [
            'title' => 'Call Us',
            'icon' => 'ri-phone-fill',
            'content' => '+60 12-697-5464'
        ],
        [
            'title' => 'Email Us',
            'icon' => 'ri-mail-fill',
            'content' => 'jane@example.com'
        ],
        [
            'title' => 'Office',
            'icon' => 'ri-map-pin-2-fill',
            'content' => 'Wisma Suria, Cyber 5 63000, Cyberjaya'
        ]
    ];
@endphp

@section('content')
<main class="bg-background overflow-hidden">
    <x-breadcrumb title="Contact" />
    <section class="container pt-5 sm:pt-7 lg:pt-12">
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl items-center justify-center mx-auto">
            @foreach ($contacts as $idx => $contact)
                <div data-aos="fade-up" data-aos-delay="{{ ($idx + 2) * 150 }}" class="flex flex-col items-center max-w-xs h-full mx-auto w-full bg-background border border-border rounded-2xl p-8 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="w-16 h-16 rounded-full bg-accent-2/10 dark:bg-accent-2/20 text-accent-2 mx-auto flex items-center justify-center">
                        <x-icon name="{{ $contact['icon'] }}" class="size-8" />
                    </div>
                    <div class="space-y-2 mt-6">
                        <h3 class="text-xl font-bold text-foreground">{{ $contact['title'] }}</h3>
                        <p class="mt-2 font-semibold mx-auto text-muted leading-tight">
                            {{ $contact['content'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center mx-auto max-w-7xl">
            <div data-aos="fade-right" class="relative w-full max-w-xl aspect-square rounded-tl-4xl rounded-br-4xl overflow-hidden mx-auto">
                <img src="/assets/contact/form.jpg" alt="Contact" class="w-full h-full object-cover">
            </div>
            <form data-aos="fade-left" class="lg:order-1 w-full max-w-xl mx-auto space-y-4">
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
                <button type="button" class="flex items-center justify-center w-full gap-2 py-3 px-6 font-bold cursor-pointer text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                    <span>Send Message</span>
                    <x-icon name="ri-send-plane-line" class="size-5" />
                </button>
            </form>
        </div>
    </section>
    <section x-data="{ isShow: false }" x-intersect.once="isShow = true" class="bg-foreground/5 rounded-lg shadow-xl min-h-[30rem] flex items-center justify-center">
        <template x-if="!isShow">
            <div class="text-muted font-bold">Loading...</div>
        </template>
        <template x-if="isShow">
            <iframe
                class="w-full h-[30rem] border-0"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.654333476605!2d101.65189417438418!3d2.91541569706097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4c0075e78e3b%3A0xd5583e5157e93403!2sImotion%20Production!5e0!3m2!1sid!2sid!4v1712376514197!5m2!1sid!2sid">
            </iframe>
        </template>
    </section>
</main>
@endsection
