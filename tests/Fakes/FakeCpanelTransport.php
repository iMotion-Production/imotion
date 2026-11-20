<?php

namespace Tests\Fakes;

use App\Services\Cpanel\CpanelTransport;
use App\Services\CpanelMailException;

/**
 * In-memory cPanel transport for tests: records every call and answers from
 * a canned list reply and a canned write reply (UAPI envelope shape).
 */
class FakeCpanelTransport implements CpanelTransport
{
    /** @var list<array{module: string, function: string, params: array, write: bool}> */
    public array $calls = [];

    public function __construct(
        public array $rows = [],
        public array $writeReply = ['status' => 1, 'errors' => null, 'data' => null],
    ) {}

    public function name(): string
    {
        return 'fake';
    }

    public function call(string $module, string $function, array $params, bool $write = false): mixed
    {
        $this->calls[] = compact('module', 'function', 'params', 'write');

        $reply = $function === 'list_pops_with_disk'
            ? ['status' => 1, 'errors' => null, 'data' => $this->rows]
            : $this->writeReply;

        if (($reply['status'] ?? 0) !== 1) {
            throw new CpanelMailException((string) ($reply['errors'][0] ?? 'unknown error'));
        }

        return $reply['data'] ?? [];
    }

    /** @return list<array> params of every call to $function */
    public function sent(string $function): array
    {
        return array_values(array_map(
            fn ($c) => $c['params'],
            array_filter($this->calls, fn ($c) => $c['function'] === $function),
        ));
    }
}
