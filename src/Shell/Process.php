<?php


namespace Sinnbeck\LaravelServed\Shell;

use Symfony\Component\Process\Process as BaseProcess;

class Process extends BaseProcess
{
    public function createCommand($command, $env = [])
    {
        $env += $this->internal_getDefaultEnv();

        $command = $this->privateMethod($this, 'replacePlaceholders')->invoke($this, $command, $env);
        return $command;
    }

    protected function internal_getDefaultEnv() {
        return $this->privateMethod($this, 'getDefaultEnv')->invoke($this);
    }

    protected function internal_replacePlaceholders($command, $env = []) {
        return $this->privateMethod($this, 'replacePlaceholders')->invoke($this);
    }

    private function privateMethod($object, $method) {
        $object = new \ReflectionObject($object);
        $method = $object->getMethod($method);
        $method->setAccessible(true);
        return $method;
    }
}
