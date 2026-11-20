<?php

namespace App\Services;

use RuntimeException;

class CpanelMailException extends RuntimeException
{
    public function isDuplicate(): bool
    {
        return str_contains(strtolower($this->getMessage()), 'already exists');
    }
}
