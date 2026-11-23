@extends('mailbox.layout')

@section('page_title', 'Password set')
@section('body', 'auth')
@section('auth', '1')

@section('content')
    <h1>Your password is set</h1>
    <p class="sub">{{ $address }}</p>

    @if (! empty($action))
        <form method="post" action="{{ $action }}">
            <input type="hidden" name="session" value="{{ $session ?? '' }}">
            <button class="btn solid">Open webmail</button>
        </form>
    @endif

    <p class="muted">{{ \App\Http\Controllers\Mailbox\MailboxController::BOOKMARK }}</p>
    <p><strong>{{ $bookmark }}</strong></p>
@endsection