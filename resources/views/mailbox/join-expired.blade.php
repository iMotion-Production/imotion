@extends('mailbox.layout')

@section('page_title', 'Link expired')
@section('body', 'auth')
@section('auth', '1')
@section('no_referrer', '1')

@section('content')
    <p class="msg" role="status">This link has expired or was already used. Ask your administrator for a new one.</p>
@endsection