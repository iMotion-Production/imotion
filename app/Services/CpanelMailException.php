<?php

namespace App\Services;

use RuntimeException;

class CpanelMailException extends RuntimeException
{
    /**
     * @param  string  $detail  diagnostic text for the log only (trimmed stderr), never shown on a page
     */
    public function __construct(string $message, public readonly string $detail = '')
    {
        parent::__construct($message);
    }

    public function isDuplicate(): bool
    {
        return str_contains(strtolower($this->getMessage()), 'already exists');
    }
}
