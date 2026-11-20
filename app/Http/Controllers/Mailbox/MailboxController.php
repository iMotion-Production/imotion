<?php

namespace App\Http\Controllers\Mailbox;

use App\Http\Controllers\Controller;
use App\Services\CpanelMail;
use App\Services\CpanelMailException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MailboxController extends Controller
{
    public const UNAVAILABLE = 'Mailbox service is unavailable right now. iMotion has been notified.';

    public const RESERVED = ['cpanel', 'postmaster', 'abuse', 'webmaster', 'root', 'admin'];

    public function __construct(private CpanelMail $cpanel) {}

    public function index(Request $request): View
    {
        $domain = $request->user()->mail_domain;
        $max = (int) config('services.cpanel.max_mailboxes', 20);
        $mailboxes = [];
        $failed = false;

        try {
            $mailboxes = $this->cpanel->list($domain);
        } catch (CpanelMailException $e) {
            $this->logFailure('list_pops_with_disk', '@'.$domain, $e);
            $failed = true;
        }

        return view('mailbox.index', [
            'domain' => $domain,
            'mailboxes' => array_map([self::class, 'withDisplaySizes'], $mailboxes),
            'failed' => $failed,
            'canAdd' => ! $failed && count($mailboxes) < $max,
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $domain = $request->user()->mail_domain;
        $request->merge(['username' => strtolower(trim((string) $request->input('username')))]);

        $data = $request->validate([
            'username' => ['required', 'string', 'regex:'.CpanelMail::USER_PATTERN],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ]);

        if (in_array($data['username'], self::RESERVED, true)) {
            return back()->withErrors(['username' => 'This name is reserved; ask iMotion.'])->withInput($request->only('username'));
        }

        $address = $data['username'].'@'.$domain;

        try {
            $count = count($this->cpanel->list($domain));
            if ($count >= (int) config('services.cpanel.max_mailboxes', 20)) {
                abort(422);
            }
            $this->cpanel->add($domain, $data['username'], $data['password']);
        } catch (CpanelMailException $e) {
            if ($e->isDuplicate()) {
                return back()->withErrors(['username' => 'This address already exists.'])->withInput($request->only('username'));
            }
            $this->logFailure('add_pop', $address, $e);

            return back()->with('error', self::UNAVAILABLE)->withInput($request->only('username'));
        }

        return redirect()->route('mailbox.index')->with('success', $address)->with('added', true);
    }

    public function password(Request $request, string $address): RedirectResponse
    {
        $domain = $request->user()->mail_domain;
        $address = strtolower($address);
        $user = $this->ownUser($domain, $address);

        $request->validate([
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ]);

        try {
            $this->cpanel->setPassword($domain, $user, (string) $request->input('password'));
        } catch (CpanelMailException $e) {
            $this->logFailure('passwd_pop', $address, $e);

            return back()->with('error', self::UNAVAILABLE);
        }

        return redirect()->route('mailbox.index')->with('success', $address);
    }

    /**
     * One-click webmail: a fresh cPanel session for the mailbox, handed to the browser
     * as a self-submitting POST form. The session value is never logged or stored.
     */
    public function webmail(Request $request, string $address): Response|RedirectResponse
    {
        $domain = $request->user()->mail_domain;
        $address = strtolower($address);
        $user = $this->ownUser($domain, $address);

        try {
            $login = $this->cpanel->webmailSession($domain, $user, (string) $request->ip());
        } catch (CpanelMailException $e) {
            $this->logFailure('create_webmail_session_for_mail_user', $address, $e, 'Session');

            return redirect()->route('mailbox.index')->with('error', self::UNAVAILABLE);
        }

        $hostname = $login['hostname'] ?? (string) config('services.cpanel.webmail_host', 'lux.ix-dns.com');

        return response()
            ->view('mailbox.webmail-redirect', [
                'action' => 'https://'.$hostname.':2096'.$login['token'].'/login',
                'session' => $login['session'],
            ])
            ->header('Cache-Control', 'no-store, private')
            ->header('Referrer-Policy', 'no-referrer');
    }

    /** cPanel answers `None` for an unlimited quota and for no disk use yet. */
    public static function withDisplaySizes(array $box): array
    {
        $empty = fn ($v) => $v === null || $v === '' || in_array(strtolower((string) $v), ['none', 'unlimited'], true);

        $used = $box['humandiskused'] ?? ($box['diskused'] ?? null);
        $quota = $box['humandiskquota'] ?? ($box['diskquota'] ?? null);

        $box['used_display'] = $empty($used) ? '0 B' : (string) $used;
        $box['quota_display'] = $empty($quota) ? 'Unlimited' : (string) $quota;

        return $box;
    }

    /**
     * The domain is never taken from the request: the address must belong to the
     * user's own domain and its local part must be a valid mailbox user (404 otherwise).
     */
    private function ownUser(string $domain, string $address): string
    {
        $suffix = '@'.strtolower($domain);
        abort_unless(str_ends_with($address, $suffix), 404);
        $user = substr($address, 0, -strlen($suffix));
        abort_unless(preg_match(CpanelMail::USER_PATTERN, $user) === 1, 404);

        return $user;
    }

    private function logFailure(string $function, string $address, CpanelMailException $e, string $module = 'Email'): void
    {
        Log::warning('cPanel mailbox call failed', [
            'function' => $module.'/'.$function,
            'mailbox' => $address,
            'error' => $e->getMessage(),
            'detail' => $e->detail,
        ]);
    }
}
