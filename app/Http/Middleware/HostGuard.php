<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The same app answers on APP_URL's host and on client portal hostnames
 * (e.g. mailbox.fokusteguhdinamik.com.my). On any host other than APP_URL's
 * (or www. of it) only the mailbox portal is served; the marketing site never renders.
 */
class HostGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        $appHost = strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST));
        $host = strtolower($request->getHost());

        if ($appHost === '' || $host === $appHost || $host === 'www.'.$appHost) {
            return $next($request);
        }

        $path = $request->path();

        if ($path === '/') {
            return redirect('/mailbox/login');
        }

        if ($path === 'mailbox' || str_starts_with($path, 'mailbox/')) {
            return $next($request);
        }

        // Plain 404: the app's error view uses the marketing layout, which must not render here.
        return response('Not Found', 404, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
