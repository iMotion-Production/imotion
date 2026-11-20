<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Only users with a mail_domain (hosting clients) may use /mailbox pages. */
class EnsureMailClient
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(blank($request->user()?->mail_domain), 403);

        return $next($request);
    }
}
