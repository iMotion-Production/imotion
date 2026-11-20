@extends('mailbox.layout')

@section('title', 'Mailboxes')

@section('content')
    <h1><span>Mailboxes for {{ $domain }}</span><svg class="i" aria-hidden="true"><use href="#i-info"/></svg></h1>

    @unless ($failed)
        <dl class="stats">
            <div class="stat"><span class="tile v"><svg class="i" aria-hidden="true"><use href="#i-mail"/></svg></span><div><dt>Mailboxes</dt><dd>{{ count($mailboxes) }}</dd></div></div>
            <div class="stat"><span class="tile n"><svg class="i" aria-hidden="true"><use href="#i-disk"/></svg></span><div><dt>Disk used</dt><dd>{{ $diskUsed }}</dd></div></div>
        </dl>
    @endunless

    @if (session('success'))
        <div class="flash" role="status"><span>Done: {{ session('success') }}</span>@if (session('added'))<form class="webmail" method="POST" action="{{ route('mailbox.webmail', ['address' => session('success')]) }}" target="_blank" rel="noopener">@csrf<button type="submit">Open webmail</button></form>@endif</div>
    @endif
    @if (session('error') || $failed)
        <div class="flash error" role="alert">{{ session('error') ?? \App\Http\Controllers\Mailbox\MailboxController::unavailable() }}</div>
    @endif
    @error('password') <div class="flash error" role="alert">{{ $message }}</div> @enderror

    @unless ($failed)
        <div class="toolbar">
            <span class="domain"><svg class="i" aria-hidden="true"><use href="#i-globe"/></svg>{{ $domain }}</span>
            @if ($canAdd)
                <button type="button" class="btn primary" data-open="#add" aria-controls="add"><svg class="i" aria-hidden="true"><use href="#i-plus"/></svg>Add mailbox</button>
            @else
                <p class="note">To add more mailboxes, contact {{ \App\Support\Portal::contact() }}.</p>
            @endif
        </div>

        @if ($canAdd)
            <section class="panel{{ $errors->has('username') ? ' open' : '' }}" id="add" aria-labelledby="add-h">
                <h2 id="add-h">Add mailbox</h2>
                <form method="POST" action="{{ route('mailbox.add') }}">
                    @csrf
                    <label for="username">Username</label>
                    <div class="suffix">
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required maxlength="64" autocapitalize="none" spellcheck="false" autocomplete="off" @error('username') aria-invalid="true" aria-describedby="username-err" @enderror>
                        <span>{{ '@'.$domain }}</span>
                    </div>
                    @error('username') <p class="err" id="username-err">{{ $message }}</p> @enderror

                    <label for="new-password">Password</label>
                    <input id="new-password" type="password" name="password" required minlength="10" autocomplete="new-password" data-pw>
                    <label for="new-password-confirmation">Confirm password</label>
                    <input id="new-password-confirmation" type="password" name="password_confirmation" required minlength="10" autocomplete="new-password" data-pw>
                    <label class="show"><input type="checkbox" data-show-password> Show password</label>

                    <button type="submit" class="btn solid">Add mailbox</button>
                </form>
            </section>
        @endif

        <div class="search"><svg class="i" aria-hidden="true"><use href="#i-search"/></svg><input type="search" placeholder="Search" aria-label="Search" data-search></div>

        <div class="list"><table>
            <thead><tr><th><span><svg class="i" aria-hidden="true"><use href="#i-user"/></svg>Address</span></th><th><span><svg class="i" aria-hidden="true"><use href="#i-disk"/></svg>Disk used</span></th><th><span><svg class="i" aria-hidden="true"><use href="#i-settings"/></svg>Quota</span></th><th><span><svg class="i" aria-hidden="true"><use href="#i-check"/></svg>Status</span></th><th class="act"><span>Action</span></th></tr></thead>
            <tbody>
            @forelse ($mailboxes as $box)
                @php
                    $address = strtolower($box['email'] ?? ($box['login'] ?? ''));
                    $webmail = route('mailbox.webmail', ['address' => $address]);
                    $suspended = ! empty($box['suspended_login']);
                @endphp
                <tr class="row" data-address="{{ $address }}">
                    <td class="addr"><strong>{{ $address }}</strong><form class="webmail" method="POST" action="{{ $webmail }}" target="_blank" rel="noopener">@csrf<button type="submit" class="web">Open webmail</button></form></td>
                    <td class="used" data-label="Disk used">{{ $box['used_display'] }}</td>
                    <td class="q" data-label="Quota">@if ($box['quota_display'] === 'Unlimited')<span class="pill ok"><svg class="i" aria-hidden="true"><use href="#i-check"/></svg>Unlimited</span>@else<span class="pill neutral">{{ $box['quota_display'] }}</span>@endif</td>
                    <td class="st" data-label="Status">@if ($suspended)<span class="pill warn">Suspended</span>@else<span class="pill ok"><svg class="i" aria-hidden="true"><use href="#i-check"/></svg>Active</span>@endif</td>
                    <td class="act">
                        <details class="menu"><summary aria-label="Action: {{ $address }}"><svg class="i" aria-hidden="true"><use href="#i-dots"/></svg></summary>
                            <div class="pop"><a href="#reset-{{ $loop->index }}"><svg class="i" aria-hidden="true"><use href="#i-key"/></svg>Reset password</a><form class="webmail" method="POST" action="{{ $webmail }}" target="_blank" rel="noopener">@csrf<button type="submit"><svg class="i" aria-hidden="true"><use href="#i-out"/></svg>Open webmail</button></form></div></details>
                        <div class="links"><a href="#reset-{{ $loop->index }}">Reset password</a><form class="webmail" method="POST" action="{{ $webmail }}" target="_blank" rel="noopener">@csrf<button type="submit">Open webmail</button></form></div>
                    </td>
                </tr>
                <tr class="panel-row" id="reset-{{ $loop->index }}"><td colspan="5"><div class="panel">
                    <h2>Reset password</h2><p class="sub">{{ $address }}</p>
                    <form method="POST" action="{{ route('mailbox.password', ['address' => $address]) }}">
                        @csrf
                        <label for="pw-{{ $loop->index }}">Password</label>
                        <input id="pw-{{ $loop->index }}" type="password" name="password" required minlength="10" autocomplete="new-password" data-pw>
                        <label for="pwc-{{ $loop->index }}">Confirm password</label>
                        <input id="pwc-{{ $loop->index }}" type="password" name="password_confirmation" required minlength="10" autocomplete="new-password" data-pw>
                        <label class="show"><input type="checkbox" data-show-password> Show password</label>
                        <button type="submit" class="btn solid">Reset password</button>
                    </form>
                </div></td></tr>
            @empty
                <tr><td class="empty" colspan="5">No mailboxes yet.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    @endunless

    <footer>IMAP mail.{{ $domain }} port 993 (SSL) · SMTP mail.{{ $domain }} port 465 (SSL) · Webmail https://{{ $domain }}/webmail</footer>
@endsection
