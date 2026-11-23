@extends('mailbox.layout')

@section('page_title', 'Set your password')
@section('body', 'auth')
@section('auth', '1')
@section('no_referrer', '1')

@section('content')
    <h1>Set your password</h1>
    <p class="addr">{{ $address }}</p>

    @if (session('error'))
        <div class="flash error" role="alert">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('mailbox.join.store', ['token' => request()->route('token')]) }}">
        @csrf
        <input type="email" name="username" value="{{ $address }}" autocomplete="username" hidden>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required minlength="12" autocomplete="new-password" data-pw @error('password') aria-invalid="true" aria-describedby="password-err" @enderror>
        @error('password')<p class="err" id="password-err">{{ $message }}</p>@enderror

        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required minlength="12" autocomplete="new-password" data-pw>

        <div class="pw-tools">
            <label class="show"><input type="checkbox" data-show-password> Show password</label>
            <button type="button" class="link-btn" data-generate-password>Generate password</button>
        </div>

        <button type="submit" class="btn solid">Set password</button>
    </form>
@endsection