<?php
declare(strict_types = 1);

namespace Tests\Innmind\Async\TimeWarp;

use Innmind\Async\TimeWarp\Halt;
use Innmind\Mantle\{
    Forerunner,
    Source\Predetermined,
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

    public function testFunctional()
    {
        $queue = new \SplQueue;
        $clock = new Clock;
        $source = Predetermined::of(
            static function(Suspend $suspend) use ($clock, $queue) {
                $halt = Halt::of($clock, $suspend);

                $halt(new Second(2));

                $queue->push('World !');
            },
            static function(Suspend $suspend) use ($clock, $queue) {
                $halt = Halt::of($clock, $suspend);

                $halt(new Second(1));

                $queue->push('Hello ');
            },
        );

        Forerunner::of($clock)(null, $source);

        $this->assertSame(
            ['Hello ', 'World !'],
            \iterator_to_array($queue),
        );
    }
}
