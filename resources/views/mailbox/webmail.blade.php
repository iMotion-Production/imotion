@extends('mailbox.layout')

@section('page_title', 'Webmail')
@section('body', 'auth')
@section('auth', '1')

@section('content')
    <h1>Webmail</h1>

    {{-- MP-04: posts straight to the client's cPanel webmail on port 2096, so no @csrf token here. --}}
    <form method="post" action="{{ $action }}">
        <label for="user">Email address</label>
        <input id="user" type="email" name="user" required autofocus autocomplete="username">

        <label for="pass">Password</label>
        <input id="pass" type="password" name="pass" required autocomplete="current-password" data-pw>
        <label class="show"><input type="checkbox" data-show-password> Show password</label>

        <button type="submit" class="btn solid">Log in</button>
    </form>

    <p class="muted">Forgot your password? Contact your administrator.</p>
@endsection