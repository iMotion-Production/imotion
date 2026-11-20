<?php

namespace App\Console\Commands;

use App\Services\CpanelMail;
use App\Services\CpanelMailException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MailboxDoctor extends Command
{
    protected $signature = 'mailbox:doctor {--domain= : The client domain to list, e.g. imotion.my}';

    protected $description = 'Live check: list mailboxes for a domain through the configured cPanel transport (prints no passwords)';

    public function handle(CpanelMail $cpanel): int
    {
        $domain = strtolower(trim((string) $this->option('domain')));

        if (! preg_match('/^(?=.{1,253}$)([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/', $domain)) {
            $this->error('Give a valid --domain, e.g. --domain=imotion.my');

            return self::FAILURE;
        }

        $this->line('Transport: '.$cpanel->transportName());

        try {
            $count = count($cpanel->list($domain));
        } catch (CpanelMailException $e) {
            Log::warning('cPanel mailbox call failed', [
                'function' => 'Email/list_pops_with_disk',
                'mailbox' => '@'.$domain,
                'error' => $e->getMessage(),
                'detail' => $e->detail,
                'via' => 'mailbox:doctor',
            ]);
            $this->error('Status: failed ('.$e->getMessage().'), details in storage/logs');

            return self::FAILURE;
        }

        $this->info('Status: ok');
        $this->line('Mailboxes on '.$domain.': '.$count);

        return self::SUCCESS;
    }
}
