<?php

namespace Sinnbeck\LaravelServed\Traits;

use RuntimeException;

trait Storage
{
    /**
     * @param bool $relative
     * @return string
     */
    protected function storageDirectory($relative = false): string
    {
        $basePath = 'app/served/' . $this->name();
        $storagePath = storage_path($basePath);

        if (!is_dir($storagePath)) {
            if (!mkdir($storagePath, 0777, true) && !is_dir($storagePath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $storagePath));
            }
        }

        if ($relative) {
            $storagePath = 'storage/' . $basePath;
        }

        return $storagePath . '/';
    }
}
