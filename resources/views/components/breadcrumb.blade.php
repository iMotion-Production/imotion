<section class="py-10 sm:py-12 lg:py-14 bg-background">
    <div class="container text-center">
        <h1 class="text-3xl lg:text-4xl font-bold text-foreground">
            {{ $title }}
        </h1>
        <nav aria-label="Breadcrumb" class="mt-3 lg:mt-6">
            <ol class="flex items-center justify-center flex-wrap gap-x-3 gap-y-2 text-sm md:text-base text-foreground/80">
                @foreach ($breadcrumbs as $index => $breadcrumb)
                    @continue($breadcrumb['title'] === $title)
                    <li>
                        <a href="{{ $breadcrumb['url'] ?? '#' }}" class="hover:text-accent-1 transition-colors">
                            {{ $breadcrumb['title'] }}
                        </a>
                    </li>
                    @if ($index < count($breadcrumbs) - 1)
                        <li aria-hidden="true">
                            <x-icon name="ri-arrow-right-line" class="size-5" />
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
</section>
