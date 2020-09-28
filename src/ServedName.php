<?php

namespace Sinnbeck\LaravelServed;

use Illuminate\Support\Str;
use Sinnbeck\LaravelServed\Exceptions\InvalidNamingException;

class ServedName
{
    /**
     * @return string
     */
    public function projectName(): string
    {
        if ($configName = config('served.name')) {
            if (!$this->testAllowedCharacters($configName)) {
                throw new InvalidNamingException($configName . ' is not a valid name!');
            }

            return $configName;
        }

        return Str::slug($this->getProjectFolderName(), '_');
    }

    protected function testAllowedCharacters($name)
    {
        preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_.-]+/', $name, $matches);

        return $matches && $matches[0] === $name;
    }

    public function getProjectFolderName(): string
    {
        return Str::afterLast(base_path(), '/');
    }
}