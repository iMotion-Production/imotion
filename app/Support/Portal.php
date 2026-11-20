<?php

namespace App\Support;

use App\Models\User;

/**
 * Who the mailbox portal is branded for. A client portal host (any host other than
 * APP_URL's, or www. of it) or a logged-in user outside imotion.my is a client view:
 * no iMotion mark and no iMotion wording (Project Manager, 2026-11-20).
 */
class Portal
{
    public const HOME_DOMAIN = 'imotion.my';

    public static function onClientHost(): bool
    {
        $appHost = strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST));
        $host = strtolower(request()->getHost());

        return $appHost !== '' && $host !== $appHost && $host !== 'www.'.$appHost;
    }

    public static function isClient(): bool
    {
        $user = auth()->user();

        return self::onClientHost() || ($user && strtolower((string) $user->mail_domain) !== self::HOME_DOMAIN);
    }

    /** The logged-in user, else the user whose portal_host is this request host. */
    public static function brandUser(): ?User
    {
        return auth()->user() ?? User::where('portal_host', strtolower(request()->getHost()))->first();
    }

    /** Who to contact, in the viewer's branding. */
    public static function contact(): string
    {
        return self::isClient() ? 'your administrator' : 'iMotion';
    }
}
