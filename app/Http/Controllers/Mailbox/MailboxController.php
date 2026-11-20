<?php

namespace App\Http\Controllers\Mailbox;

use App\Http\Controllers\Controller;
use App\Services\CpanelMail;
use App\Services\CpanelMailException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'mailboxes' => $mailboxes,
            'failed' => $failed,
            'canAdd' => ! $failed && count($mailboxes) < $max,
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $domain = $request->user()->mail_domain;
        $request->merge(['username' => strtolower(trim((string) $request->input('username')))]);

        $data = $request->validate([
            'username' => ['required', 'string', 'regex:/^[a-z0-9][a-z0-9._-]{0,63}$/'],
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

        return redirect()->route('mailbox.index')->with('success', $address);
    }

    public function password(Request $request, string $address): RedirectResponse
    {
        $domain = $request->user()->mail_domain;
        $address = strtolower($address);
        $suffix = '@'.strtolower($domain);

        // The domain is never taken from the request: the address must belong to the user's own domain.
        abort_unless(str_ends_with($address, $suffix), 404);
        $user = substr($address, 0, -strlen($suffix));
        abort_unless(preg_match('/^[a-z0-9][a-z0-9._-]{0,63}$/', $user) === 1, 404);

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

    private function logFailure(string $function, string $address, CpanelMailException $e): void
    {
        Log::warning('cPanel mailbox call failed', [
            'function' => 'Email/'.$function,
            'mailbox' => $address,
            'error' => $e->getMessage(),
        ]);
    }
}
