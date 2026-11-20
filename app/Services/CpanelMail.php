<?php

namespace App\Services;

use App\Services\Cpanel\CpanelTransport;
use InvalidArgumentException;

/**
 * The three cPanel UAPI calls the mailbox portal is allowed to make.
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

    /** Second line of defence after the controller: nothing else reaches argv. */
    private function assertUser(string $user): void
    {
        if (preg_match(self::USER_PATTERN, $user) !== 1) {
            throw new InvalidArgumentException('Invalid mailbox user.');
        }
    }
}
