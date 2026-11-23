<?php

namespace App\Http\Controllers\Mailbox;

use App\Models\MailboxInvite;
use App\Services\CpanelMail;
use App\Services\CpanelMailException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * The public "set your password" pages reached from an invite link (mailbox/join/{token}).
 * The token is a credential: it stays in the URL, is never logged, and the mailbox domain and
 * user always come from the invite row, never from the request.
 * Every response carries no-store and no-referrer headers (the URL holds the token).
 */
class InviteController
{
    public const NO_STORE = 'no-store, private';

    public const NO_REFERRER = 'no-referrer';

    public function __construct(private CpanelMail $cpanel) {}

    public function show(Request $request, string $token): Response
    {
        $invite = $this->usableOnThisHost($request, $token);

        if ($invite === null) {
            return $this->expired();
        }

        return $this->view('mailbox.join', ['address' => $this->address($invite)]);
    }

    public function store(Request $request, string $token): Response|RedirectResponse
    {
        $invite = $this->usableOnThisHost($request, $token);

        if ($invite === null) {
            return $this->expired();
        }

        $request->validate([
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);

        $password = (string) $request->input('password');
        $address = $this->address($invite);

        try {
            $this->cpanel->setPassword($invite->mail_domain, $invite->mailbox_user, $password);
        } catch (CpanelMailException $e) {
            if ($e->isWeakPassword()) {
                return back()->withErrors(['password' => MailboxController::WEAK_PASSWORD]);
            }
            MailboxController::logCpanelFailure('passwd_pop', $address, $e);

            return back()->with('error', MailboxController::unavailable());
        }

        $invite->forceFill(['used_at' => now()])->save();

        $host = $this->host($invite);
        $session = null;

        try {
            $login = $this->cpanel->webmailSession($invite->mail_domain, $invite->mailbox_user, (string) $request->ip());
            $hostname = $login['hostname'] ?? (string) config('services.cpanel.webmail_host', 'lux.ix-dns.com');
            $session = [
                'action' => 'https://'.$hostname.':2096'.$login['token'].'/login',
                'session' => $login['session'],
            ];
        } catch (CpanelMailException $e) {
            // The password is already set: no form, just the page.
            MailboxController::logCpanelFailure('create_webmail_session_for_mail_user', $address, $e, 'Session');
        }

        return $this->view('mailbox.join-done', [
            'address' => $address,
            'bookmark' => 'https://'.$host.'/mailbox/webmail',
            'action' => $session['action'] ?? null,
            'session' => $session['session'] ?? null,
        ]);
    }

    /**
     * The invite the token stands for, only on the inviting admin's own portal host
     * (or any host when that admin has no portal_host). A null answer is the expired page;
     * a foreign host is a 404 before any lookup result is shown.
     */
    private function usableOnThisHost(Request $request, string $token): ?MailboxInvite
    {
        $invite = MailboxInvite::findUsable($token);

        if ($invite === null) {
            return null;
        }

        $portalHost = strtolower(trim((string) $invite->user?->portal_host));

        abort_if($portalHost !== '' && $portalHost !== strtolower($request->getHost()), 404);

        return $invite;
    }

    /** Where invite links for this mailbox are served. */
    private function host(MailboxInvite $invite): string
    {
        $portalHost = strtolower(trim((string) $invite->user?->portal_host));

        return $portalHost !== '' ? $portalHost : strtolower(request()->getHost());
    }

    private function address(MailboxInvite $invite): string
    {
        return strtolower($invite->mailbox_user.'@'.$invite->mail_domain);
    }

    private function expired(): Response
    {
        return $this->page('mailbox.join-expired')->setStatusCode(410);
    }

    private function view(string $name, array $data = []): Response
    {
        return $this->page($name, $data);
    }

    private function page(string $name, array $data = []): Response
    {
        return response()
            ->view($name, $data)
            ->header('Cache-Control', self::NO_STORE)
            ->header('Referrer-Policy', self::NO_REFERRER);
    }
}