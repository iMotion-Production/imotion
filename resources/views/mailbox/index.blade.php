@extends('mailbox.layout')

@section('title', 'Mailboxes')

@section('content')
    <h1>Mailboxes for {{ $domain }}</h1>

    @if (session('success'))
        <div class="flash" role="status">Done: {{ session('success') }}</div>
    @endif
    @if (session('error') || $failed)
        <div class="flash error" role="alert">{{ session('error') ?? \App\Http\Controllers\Mailbox\MailboxController::UNAVAILABLE }}</div>
    @endif
    @error('password') <div class="flash error" role="alert">{{ $message }}</div> @enderror

    @unless ($failed)
        <table>
            <thead>
                <tr><th>Address</th><th>Disk used</th><th class="q">Quota</th></tr>
            </thead>
            <tbody>
            @foreach ($mailboxes as $box)
                @php($address = strtolower($box['email'] ?? ($box['login'] ?? '')))
                <tr>
                    <td class="addr">
                        {{ $address }}
                        <details>
                            <summary>Reset password</summary>
                            <form method="POST" action="{{ route('mailbox.password', ['address' => $address]) }}">
                                @csrf
                                <label for="pw-{{ $loop->index }}">Password</label>
                                <input id="pw-{{ $loop->index }}" type="password" name="password" required minlength="10" autocomplete="new-password" data-pw>
                                <label for="pwc-{{ $loop->index }}">Confirm password</label>
                                <input id="pwc-{{ $loop->index }}" type="password" name="password_confirmation" required minlength="10" autocomplete="new-password" data-pw>
                                <label class="show"><input type="checkbox" data-show-password> Show password</label>
                                <button type="submit">Reset password</button>
                            </form>
                        </details>
                    </td>
                    <td>{{ $box['humandiskused'] ?? ($box['diskused'] ?? '') }}</td>
                    <td class="q">{{ $box['humandiskquota'] ?? ($box['diskquota'] ?? '') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endunless

    @if ($canAdd)
        <h2>Add mailbox</h2>
        <form method="POST" action="{{ route('mailbox.add') }}">
            @csrf
            <label for="username">Username</label>
            <div class="suffix">
                <input id="username" type="text" name="username" value="{{ old('username') }}" required maxlength="64" autocapitalize="none" spellcheck="false" autocomplete="off">
                <span>{{ '@'.$domain }}</span>
            </div>
            @error('username') <p class="err">{{ $message }}</p> @enderror

            <label for="new-password">Password</label>
            <input id="new-password" type="password" name="password" required minlength="10" autocomplete="new-password" data-pw>
            <label for="new-password-confirmation">Confirm password</label>
            <input id="new-password-confirmation" type="password" name="password_confirmation" required minlength="10" autocomplete="new-password" data-pw>
            <label class="show"><input type="checkbox" data-show-password> Show password</label>

            <button type="submit">Add mailbox</button>
        </form>
    @endif

    <footer>IMAP mail.{{ $domain }} port 993 (SSL) · SMTP mail.{{ $domain }} port 465 (SSL) · Webmail https://{{ $domain }}/webmail</footer>
@endsection
