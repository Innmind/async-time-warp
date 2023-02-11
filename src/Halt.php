<?php
declare(strict_types = 1);

namespace Innmind\Async\TimeWarp;

use Innmind\TimeWarp\{
    Halt as HaltInterface,
    PeriodToMilliseconds,
};
use Innmind\TimeContinuum\{
    Clock,
    Period,
    Earth\ElapsedPeriod,
};
use Innmind\Mantle\Suspend;

final class Halt implements HaltInterface
{
    private Clock $clock;
    private Suspend $suspend;

    private function __construct(Clock $clock, Suspend $suspend)
    {
        $this->clock = $clock;
        $this->suspend = $suspend;
    }

    public function __invoke(Period $period): void
    {
        $expected = ElapsedPeriod::of((new PeriodToMilliseconds)($period));
        $start = $this->clock->now();

        do {
            ($this->suspend)();
        } while (!$this->clock->now()->elapsedSince($start)->longerThan($expected));
    }

    public static function of(Clock $clock, Suspend $suspend): self
    {
        return new self($clock, $suspend);
    }
}
