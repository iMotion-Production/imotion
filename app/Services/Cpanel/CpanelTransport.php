<?php

namespace App\Services\Cpanel;

use App\Services\CpanelMailException;

/**
 * One way of reaching cPanel UAPI. Implementations return the call's `data`
 * on status 1 and throw CpanelMailException on anything else.
 */
interface CpanelTransport
{
    /** Short name shown by `php artisan mailbox:doctor` (uapi | http). */
    public function name(): string;

    /**
     * @param  array<string, scalar>  $params
     * @param  bool  $write  true for calls that carry a secret or change state
     *
     * @throws CpanelMailException
     */
    public function call(string $module, string $function, array $params, bool $write = false): mixed;
}
