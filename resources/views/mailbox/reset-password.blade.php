@extends('mailbox.layout')

@section('title', 'Set password')
@section('body', 'auth')
@section('auth', '1')

@section('content')
    <h1>Set password</h1>

    <form method="POST" action="{{ route('mailbox.reset') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="username" @error('email') aria-invalid="true" aria-describedby="email-err" @enderror>
        @error('email') <p class="err" id="email-err">{{ $message }}</p> @enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required minlength="10" autocomplete="new-password" data-pw @error('password') aria-invalid="true" aria-describedby="password-err" @enderror>
        @error('password') <p class="err" id="password-err">{{ $message }}</p> @enderror

        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required minlength="10" autocomplete="new-password" data-pw>
        <label class="show"><input type="checkbox" data-show-password> Show password</label>

        <button type="submit" class="btn solid">Set password</button>
    </form>
@endsection
