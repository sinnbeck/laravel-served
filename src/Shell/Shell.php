<?php

namespace Sinnbeck\LaravelServed\Shell;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Shell
{
    public function __construct(ConsoleOutput $consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
    }

    public function run(string $command, array $env = []): void
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);

        $process->run(function($type, $buffer) {
            if (Process::ERR === $type) {
                $this->consoleOutput->writeln('ERROR : ' . $buffer);
            } else {
                $this->consoleOutput->writeln($buffer);
            }
        }, $env);

    }

    public function exec(string $command, array $env = []): string
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);

        $process->run(null, $env);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();

    }

}
