<?php

namespace App\Http\Controllers\Mailbox;

use App\Http\Controllers\Controller;
use App\Services\CpanelMail;
use App\Services\CpanelMailException;
use App\Support\Portal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MailboxController extends Controller
{
    public const UNAVAILABLE = 'Mailbox service is unavailable right now. iMotion has been notified.';

    public const UNAVAILABLE_CLIENT = 'Mailbox service is unavailable right now. Please try again later.';

    public const RESERVED = ['cpanel', 'postmaster', 'abuse', 'webmaster', 'root', 'admin'];

    public const WEAK_PASSWORD = 'This password is too easy to guess. Click Generate password for a strong one.';

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
            'diskUsed' => self::bytes((int) array_sum(array_column($mailboxes, '_diskused'))),
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
            return back()->withErrors(['username' => Portal::isClient() ? 'This name is reserved.' : 'This name is reserved; ask iMotion.'])->withInput($request->only('username'));
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
            if ($e->isWeakPassword()) {
                return back()->withErrors(['password' => self::WEAK_PASSWORD])->withInput($request->only('username'));
            }
            $this->logFailure('add_pop', $address, $e);

            return back()->with('error', self::unavailable())->withInput($request->only('username'));
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
            if ($e->isWeakPassword()) {
                return back()->withErrors(['password' => self::WEAK_PASSWORD]);
            }
            $this->logFailure('passwd_pop', $address, $e);

            return back()->with('error', self::unavailable());
        }

        return redirect()->route('mailbox.index')->with('success', $address);
    }

    /** Deletes one mailbox of the user's own domain. */
    public function destroy(Request $request, string $address): RedirectResponse
    {
        $domain = $request->user()->mail_domain;
        $address = strtolower($address);
        $user = $this->ownUser($domain, $address);

        try {
            $this->cpanel->delete($domain, $user);
        } catch (CpanelMailException $e) {
            $this->logFailure('delete_pop', $address, $e);

            return back()->with('error', self::unavailable());
        }

        return redirect()->route('mailbox.index')->with('deleted', $address);
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

            return redirect()->route('mailbox.index')->with('error', self::unavailable());
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

    /** The unavailable sentence in the viewer's branding (the log line is unchanged). */
    public static function unavailable(): string
    {
        return Portal::isClient() ? self::UNAVAILABLE_CLIENT : self::UNAVAILABLE;
    }

    /** Bytes (cPanel `_diskused`) in cPanel's humandiskused style, e.g. "1.2 MB". */
    public static function bytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = min((int) floor(log($bytes, 1024)), 4);

        return ($i === 0 ? (string) $bytes : number_format($bytes / 1024 ** $i, 1)).' '.$units[$i];
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
