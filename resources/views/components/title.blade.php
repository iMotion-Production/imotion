<div {{ $attributes->class(['text-foreground', 'text-center mx-auto' => $isCenter]) }}>
    <x-badge :title="$badge" />
    <h1 class="text-[2rem] md:text-[2.5rem] leading-small font-extrabold mt-2">
        {{ $heading }}
    </h1>
    @if (!empty($description))
        <p @class(['mt-3 text-base text-muted max-w-3xl leading-relaxed', 'mx-auto' => $isCenter])>
            {{ $description }}
        </p>
    @endif
</div>
