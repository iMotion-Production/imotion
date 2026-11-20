<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class MailboxClient extends Command
{
    protected $signature = 'mailbox:client {email} {domain} {--name=}';

    protected $description = 'Create or update a mailbox-portal client and print a one-time password-set link';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $domain = strtolower(trim($this->argument('domain')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email.');

            return self::FAILURE;
        }
        if (! preg_match('/^(?=.{1,253}$)([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/', $domain)) {
            $this->error('Invalid domain.');

            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $this->option('name') ?: ($user->name ?: $domain);
        $user->mail_domain = $domain;
        if (! $user->exists) {
            $user->password = Str::random(40);
        }
        $user->save();

        $token = Password::broker()->createToken($user);
        $url = route('mailbox.reset.show', ['token' => $token, 'email' => $email]);

        $this->info("Client {$email} set for {$domain}.");
        $this->line($url);

        return self::SUCCESS;
    }
}
