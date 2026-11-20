<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class MailboxClient extends Command
{
    protected $signature = 'mailbox:client {email} {domain} {--name=} {--host= : Client portal hostname, e.g. mailbox.example.com (empty string clears it)}';

    protected $description = 'Create or update a mailbox-portal client and print a one-time password-set link';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $domain = strtolower(trim($this->argument('domain')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email.');

            return self::FAILURE;
        }
        $hostPattern = '/^(?=.{1,253}$)([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/';
        if (! preg_match($hostPattern, $domain)) {
            $this->error('Invalid domain.');

            return self::FAILURE;
        }

        $host = $this->option('host');
        if ($host !== null) {
            $host = strtolower(trim($host));
            if ($host !== '' && ! preg_match($hostPattern, $host)) {
                $this->error('Invalid host.');

                return self::FAILURE;
            }
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $this->option('name') ?: ($user->name ?: $domain);
        $user->mail_domain = $domain;
        if ($host !== null) {
            $user->portal_host = $host === '' ? null : $host;
        }
        if (! $user->exists) {
            $user->password = Str::random(40);
        }
        $user->save();

        $token = Password::broker()->createToken($user);
        $path = route('mailbox.reset.show', ['token' => $token, 'email' => $email], false);
        $url = $user->portal_host
            ? 'https://'.$user->portal_host.$path
            : rtrim((string) config('app.url'), '/').$path;

        $this->info("Client {$email} set for {$domain}.");
        $this->line($url);

        return self::SUCCESS;
    }
}
