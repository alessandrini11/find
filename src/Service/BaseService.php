<?php

namespace App\Service;

class BaseService
{
    public function getTime(): \DateTime
    {
        return new \DateTime('now');
    }

    public function getDiffTime(\DateTime $start, \DateTime $end): string
    {
        $timeDiff = $start->diff($end);

        return $timeDiff->format('%H:%I:%S');
    }

    /**
     * Return memory usage in megabytes.
     */
    public function getMemoryUsage(): float
    {
        return round((memory_get_peak_usage(true) / (1024 * 1024)), 2);
    }
}