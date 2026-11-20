<?php

namespace App\Services\Cpanel;

use App\Services\CpanelMailException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * cPanel UAPI over HTTPS with an API token (port 2083). Kept for a host that
 * allows tokens; JimatHosting does not (Imunify360 returns 403 to scripted calls).
 */
class HttpTransport implements CpanelTransport
{
    public function name(): string
    {
        return 'http';
    }

    /**
     * Secrets (mailbox passwords) go in a form-encoded POST body so they never
     * land in a URL or the cPanel access log.
     */
    public function call(string $module, string $function, array $params, bool $write = false): mixed
    {
        $cfg = config('services.cpanel');
        $url = 'https://'.$cfg['host'].'/execute/'.$module.'/'.$function;

        $client = Http::withHeaders(['Authorization' => 'cpanel '.$cfg['user'].':'.$cfg['token']])
            ->withOptions(['verify' => true])
            ->timeout(15)
            ->acceptJson();

        try {
            $response = $write
                ? $client->asForm()->post($url, $params)
                : $client->get($url, $params);
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
