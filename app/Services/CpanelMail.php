<?php

namespace App\Services;

use App\Services\Cpanel\CpanelTransport;
use InvalidArgumentException;

/**
 * The cPanel UAPI calls the mailbox portal is allowed to make.
 * The domain argument must always come from the logged-in user's mail_domain.
 * How cPanel is reached (local `uapi` command or HTTPS) is the bound CpanelTransport.
 */
class CpanelMail
{
    public const USER_PATTERN = '/^[a-z0-9][a-z0-9._-]{0,63}$/';

    public function __construct(private CpanelTransport $transport) {}

    public function transportName(): string
    {
        return $this->transport->name();
    }

    public function list(string $domain): array
    {
        $rows = $this->transport->call('Email', 'list_pops_with_disk', ['domain' => $domain]);

        $suffix = '@'.strtolower($domain);

        return array_values(array_filter((array) $rows, function ($row) use ($domain, $suffix) {
            $rowDomain = strtolower((string) ($row['domain'] ?? ''));
            $email = strtolower((string) ($row['email'] ?? ''));

            return $rowDomain === strtolower($domain) || str_ends_with($email, $suffix);
        }));
    }

    public function add(string $domain, string $user, string $password): void
    {
        $this->assertUser($user);

        $this->transport->call('Email', 'add_pop', [
            'email' => $user,
            'domain' => $domain,
            'password' => $password,
            'quota' => 0,
            'send_welcome_email' => 0,
        ], true);
    }

    public function setPassword(string $domain, string $user, string $password): void
    {
        $this->assertUser($user);

        $this->transport->call('Email', 'passwd_pop', [
            'email' => $user,
            'domain' => $domain,
            'password' => $password,
        ], true);
    }

    public function delete(string $domain, string $user): void
    {
        $this->assertUser($user);

        $this->transport->call('Email', 'delete_pop', ['email' => $user, 'domain' => $domain, 'flush' => 1], true);
    }

    /**
     * One-time webmail login for a mailbox (Session::create_webmail_session_for_mail_user).
     * The returned session is a credential: it goes to the browser once and is never logged or stored.
     *
     * @return array{hostname: ?string, token: string, session: string}
     */
    public function webmailSession(string $domain, string $user, string $clientIp): array
    {
        $this->assertUser($user);

        $data = (array) $this->transport->call('Session', 'create_webmail_session_for_mail_user', [
            'login' => $user,
            'domain' => $domain,
            'remote_address' => $clientIp,
        ], true);

        $hostname = isset($data['hostname']) && $data['hostname'] !== '' ? strtolower((string) $data['hostname']) : null;
        $token = (string) ($data['token'] ?? '');
        $session = (string) ($data['session'] ?? '');

        // The reply builds a URL and a form field: accept only the shapes cPanel documents.
        if (preg_match('#^/cpsess\d+$#', $token) !== 1 || $session === ''
            || ($hostname !== null && preg_match('/^[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)+$/', $hostname) !== 1)) {
            throw new CpanelMailException('unexpected webmail session reply');
        }

        return ['hostname' => $hostname, 'token' => $token, 'session' => $session];
    }

    /** Second line of defence after the controller: nothing else reaches argv. */
    private function assertUser(string $user): void
    {
        if (preg_match(self::USER_PATTERN, $user) !== 1) {
            throw new InvalidArgumentException('Invalid mailbox user.');
        }
    }
}
