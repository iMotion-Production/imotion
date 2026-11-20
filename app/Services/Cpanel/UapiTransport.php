<?php

namespace App\Services\Cpanel;

use App\Services\CpanelMailException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\RuntimeException as ProcessRuntimeException;
use Symfony\Component\Process\Process;

/**
 * cPanel UAPI through the local `uapi` command. The portal runs on the cPanel
 * server as the account user, so no token, password or network is involved.
 *
 * The command is always an argv array (never a shell string): one item per
 * argument, so nothing is quoted or interpreted by a shell. Values are passed
 * raw inside their own `key=value` item; the uapi CLI does not URI-decode them.
 */
class UapiTransport implements CpanelTransport
{
    public const TIMEOUT = 20;

    public function __construct(private string $bin = 'uapi') {}

    public function name(): string
    {
        return 'uapi';
    }

    /**
     * @param  array<string, scalar>  $params
     * @return list<string>
     */
    public function command(string $module, string $function, array $params): array
    {
        $argv = [$this->bin, '--output=json', $module, $function];

        foreach ($params as $key => $value) {
            $argv[] = $key.'='.(string) $value;
        }

        return $argv;
    }

    public function call(string $module, string $function, array $params, bool $write = false): mixed
    {
        $command = $this->command($module, $function, $params);

        try {
            [$exit, $stdout, $stderr] = $this->execute($command);
        } catch (LogicException) {
            // proc_open missing or listed in disable_functions for this PHP.
            throw new CpanelMailException('process disabled');
        } catch (ProcessTimedOutException) {
            throw new CpanelMailException('uapi timeout');
        } catch (ProcessRuntimeException $e) {
            throw new CpanelMailException('uapi failed', $this->scrub($e->getMessage(), $params));
        }

        $json = json_decode($stdout, true);

        if ($exit !== 0 || ! is_array($json)) {
            throw new CpanelMailException('uapi failed', $this->scrub($stderr !== '' ? $stderr : 'exit '.$exit, $params));
        }

        // The CLI wraps the envelope in `result`; read the top level defensively.
        $result = is_array($json['result'] ?? null) ? $json['result'] : $json;

        if ((int) ($result['status'] ?? 0) !== 1) {
            throw new CpanelMailException((string) ($result['errors'][0] ?? 'unknown error'));
        }

        return $result['data'] ?? [];
    }

    /**
     * Runs the argv array. Separate so tests can replace the process.
     *
     * @param  list<string>  $command
     * @return array{0: int, 1: string, 2: string}
     */
    protected function execute(array $command): array
    {
        $process = new Process($command, null, null, null, self::TIMEOUT);
        $process->run();

        return [(int) $process->getExitCode(), $process->getOutput(), $process->getErrorOutput()];
    }

    /** stderr for the log only: parameter values removed, one line, at most 200 chars. */
    private function scrub(string $text, array $params): string
    {
        foreach ($params as $value) {
            $value = (string) $value;
            if (strlen($value) >= 3) {
                $text = str_replace($value, '[redacted]', $text);
            }
        }

        return mb_substr(trim((string) preg_replace('/\s+/', ' ', $text)), 0, 200);
    }
}
