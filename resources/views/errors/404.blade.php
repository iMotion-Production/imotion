@extends('public/layouts/base')

@section('content')
<main class="bg-background overflow-hidden">
    <section class="container">
        <div class="flex items-center w-full min-h-[calc(100vh_-_20rem)] px-6 py-12 mx-auto">
            <div class="flex flex-col items-center max-w-sm mx-auto text-center">
                <div class="w-16 h-16 rounded-full bg-accent-1/10 dark:bg-accent-1/20 text-accent-1 mx-auto flex items-center justify-center">
                    <x-icon name="ri-info-i" class="size-8" />
                </div>
                <h1 class="mt-1.5 sm:mt-3 text-2xl md:text-3xl font-extrabold text-foreground">
                    Page not found
                </h1>
                <p class="mt-1 text-muted max-w-2xl mx-auto">
                    The page you are looking for doesn't exist.
                </p>
                <a href="{{ route('home') }}" class="mt-2 sm:mt-4 flex items-center justify-center gap-2 py-3 px-6 font-semibold text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                    <x-icon name="ri-arrow-left-line" class="size-5" />
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </section>
</main>
@endsection
