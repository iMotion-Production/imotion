@extends('mailbox.layout')

@section('page_title', 'Your password is set')
@section('body', 'auth')
@section('auth', '1')
@section('no_referrer', '1')

@section('content')
    <h1>Your password is set</h1>
    <p class="addr">{{ $address }}</p>

    @if (! empty($action))
        <form method="post" action="{{ $action }}">
            <input type="hidden" name="session" value="{{ $session ?? '' }}">
            <button class="btn solid">Open webmail</button>
        </form>
    @else
        <a class="btn solid" href="{{ $bookmark }}">Open webmail</a>
    @endif

    <p class="bookmark">{{ \App\Http\Controllers\Mailbox\MailboxController::BOOKMARK }}</p>
    <p class="url">{{ $bookmark }}</p>
@endsection