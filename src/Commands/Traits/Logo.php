<?php
/**
 * User: rene
 * Date: 9/7/20
 * Time: 8:38 AM
 * Mail: rs@aalund.com
 */

namespace Sinnbeck\LaravelServed\Commands\Traits;

trait Logo
{
    protected function drawLogo()
    {
        $this->line(' _____                   _
|   __|___ ___ _ _ ___ _| |
|__   | -_|  _| | | -_| . |
|_____|___|_|  \_/|___|___|', 'fg=blue');
    }
}
