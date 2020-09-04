<?php

namespace Sinnbeck\LaravelServed\Traits;

trait Storage
{
    /**
     * @return string
     */
    protected function storageDirectory($relative = false): string
    {
        $basePath = 'app/served/' . $this->simpleName();
        $storagePath = storage_path($basePath);

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        if ($relative) {
            $storagePath = 'storage/' . $basePath;
        }

        return $storagePath . '/';
    }
}
