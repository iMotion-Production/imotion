@extends('mailbox.layout')

@section('title', 'Log in')

@section('content')
    <h1>Log in</h1>

    @if (session('status'))
        <div class="flash" role="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('mailbox.login.attempt') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        @error('email') <p class="err">{{ $message }}</p> @enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password" data-pw>
        <label class="show"><input type="checkbox" data-show-password> Show password</label>

        <button type="submit">Log in</button>
    </form>

    <p class="muted">Forgot your password? Contact iMotion.</p>
@endsection
