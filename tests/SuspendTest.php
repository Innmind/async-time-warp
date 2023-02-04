<?php
declare(strict_types = 1);

namespace Tests\Innmind\Async\TimeWarp;

use Innmind\Async\TimeWarp\Suspend;
use Innmind\Mantle\{
    Suspend as Suspension,
    Suspend\Synchronous,
};
use Innmind\TimeContinuum\Earth\{
    Clock,
    Period\Second,
};
use PHPUnit\Framework\TestCase;

class SuspendTest extends TestCase
{
    public function testSuspend()
    {
        $suspend = Suspend::of(
            new Clock,
            Suspension::of(new Clock, Synchronous::of()),
        );

        $start = \microtime(true);

        $suspend(new Second(2));

        $this->assertGreaterThanOrEqual(2, \microtime(true) - $start);
    }
}
