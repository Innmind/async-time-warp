<?php
declare(strict_types = 1);

namespace Tests\Innmind\Async\TimeWarp;

use Innmind\Async\TimeWarp\Halt;
use Innmind\Mantle\{
    Suspend,
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
        $halt = Halt::of(
            new Clock,
            Suspend::of(new Clock, Synchronous::of()),
        );

        $start = \microtime(true);

        $halt(new Second(2));

        $this->assertGreaterThanOrEqual(2, \microtime(true) - $start);
    }
}
