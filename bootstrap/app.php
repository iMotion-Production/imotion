<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global (before routing) so unknown paths on a client host also get the plain 404, never the marketing 404 page.
        $middleware->prepend(\App\Http\Middleware\HostGuard::class);
        $middleware->redirectGuestsTo(fn () => route('mailbox.login'));
        $middleware->redirectUsersTo(fn () => route('mailbox.index'));
        $middleware->alias(['mail.client' => \App\Http\Middleware\EnsureMailClient::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
