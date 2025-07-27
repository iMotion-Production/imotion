@if (request()->is(route('home')))
    <a href="{{ route('home') }}">
        <x-logo class="h-10 w-auto text-foreground" />
    </a>
@else
    <div>
        <x-logo class="h-10 w-auto text-foreground" />
    </div>
@endif
