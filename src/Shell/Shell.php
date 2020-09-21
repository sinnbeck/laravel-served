<?php

namespace Sinnbeck\LaravelServed\Shell;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Shell
{
    /**
     * @var ConsoleOutput
     */
    private $consoleOutput;

    /**
     * Shell constructor.
     * @param ConsoleOutput $consoleOutput
     */
    public function __construct(ConsoleOutput $consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
    }

    /**
     * @param string $command
     * @param array $env
     */
    public function run(string $command, array $env = []): void
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);

        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->consoleOutput->writeln('ERROR : ' . $buffer);
            } else {
                $this->consoleOutput->writeln($buffer);
            }
        }, $env);
    }

    /**
     * @param string $command
     * @param array $env
     * @return string
     */
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
