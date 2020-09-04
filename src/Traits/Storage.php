<?php

namespace Sinnbeck\LaravelServed\Traits;

trait Storage
{
    /**
     * @return string
     */
    protected function storageDirectory(): string
    {
        $storagePath = storage_path('app/served/' . $this->simpleName());

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        return $storagePath . '/';
    }
}
