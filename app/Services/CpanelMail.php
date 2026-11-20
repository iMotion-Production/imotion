<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * The three cPanel UAPI calls the mailbox portal is allowed to make.
 * The domain argument must always come from the logged-in user's mail_domain.
 */
class CpanelMail
{
    public function list(string $domain): array
    {
        $rows = $this->call('GET', 'list_pops_with_disk', ['domain' => $domain]);

        $suffix = '@'.strtolower($domain);

        return array_values(array_filter((array) $rows, function ($row) use ($domain, $suffix) {
            $rowDomain = strtolower((string) ($row['domain'] ?? ''));
            $email = strtolower((string) ($row['email'] ?? ''));

            return $rowDomain === strtolower($domain) || str_ends_with($email, $suffix);
        }));
    }

    public function add(string $domain, string $user, string $password): void
    {
        $this->call('POST', 'add_pop', [
            'email' => $user,
            'domain' => $domain,
            'password' => $password,
            'quota' => 0,
            'send_welcome_email' => 0,
        ]);
    }

    public function setPassword(string $domain, string $user, string $password): void
    {
        $this->call('POST', 'passwd_pop', [
            'email' => $user,
            'domain' => $domain,
            'password' => $password,
        ]);
    }

    /**
     * Secrets (mailbox passwords) go in a form-encoded POST body so they never
     * land in a URL or the cPanel access log.
     */
    private function call(string $method, string $function, array $params): mixed
    {
        $cfg = config('services.cpanel');
        $url = 'https://'.$cfg['host'].'/execute/Email/'.$function;

        $client = Http::withHeaders(['Authorization' => 'cpanel '.$cfg['user'].':'.$cfg['token']])
            ->withOptions(['verify' => true])
            ->timeout(15)
            ->acceptJson();

        try {
            $response = $method === 'GET'
                ? $client->get($url, $params)
                : $client->asForm()->post($url, $params);
        } catch (ConnectionException) {
            throw new CpanelMailException('connection failed');
        }

        $json = $response->json();

        if (! $response->successful() || ! is_array($json)) {
            throw new CpanelMailException('http '.$response->status());
        }

        if (($json['status'] ?? 0) !== 1) {
            throw new CpanelMailException((string) ($json['errors'][0] ?? 'unknown error'));
        }

        return $json['data'] ?? [];
    }
}
