<?php

namespace App\Http\Controllers\Mailbox;

use App\Models\User;
use App\Support\Portal;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * MP-04: the branded webmail sign-in page on a client portal host. Public (no portal login).
 * It only renders a form that posts cross-origin to that client's cPanel webmail; the domain
 * comes from the server-side user row, never from the URL or request input.
 */
class WebmailSigninController
{
    public function show(Request $request): View
    {
        abort_unless(Portal::onClientHost(), 404);

        $user = User::where('portal_host', strtolower($request->getHost()))->first();

        abort_unless($user && preg_match('/^[a-z0-9.-]+$/', (string) $user->mail_domain) === 1, 404);

        return view('mailbox.webmail', ['action' => 'https://'.strtolower($user->mail_domain).':2096/login/']);
    }
}