@extends('mailbox.layout')

@section('title', 'Log in')
@section('body', 'auth')
@section('auth', '1')

@section('content')
    <h1>Log in</h1>

    @if (session('status'))
        <div class="flash" role="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('mailbox.login.attempt') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" @error('email') aria-invalid="true" aria-describedby="email-err" @enderror>
        @error('email') <p class="err" id="email-err">{{ $message }}</p> @enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password" data-pw>
        <label class="show"><input type="checkbox" data-show-password> Show password</label>

        <button type="submit" class="btn solid">Log in</button>
    </form>

    <p class="muted">Forgot your password? Contact {{ \App\Support\Portal::contact() }}.</p>
@endsection
