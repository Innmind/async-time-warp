# Async TimeWarp

[![Build Status](https://github.com/innmind/async-time-warp/workflows/CI/badge.svg?branch=main)](https://github.com/innmind/async-time-warp/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/innmind/async-time-warp/branch/develop/graph/badge.svg)](https://codecov.io/gh/innmind/async-time-warp)
[![Type Coverage](https://shepherd.dev/github/innmind/async-time-warp/coverage.svg)](https://shepherd.dev/github/innmind/async-time-warp)

Async implementation of [`innmind/time-warp`](https://packagist.org/packages/innmind/time-warp) to allow switching to another task when halting the current process.

## Installation

```sh
composer require innmind/async-time-warp
```

## Usage

```php
use Innmind\Async\TimeWarp\Halt;
use Innmind\TimeContinuum\Earth\{
    Clock,
    Period\Second,
};
use Innmind\Mantle\{
    Source\Predetermined,
    Suspend,
    Forerunner,
};

$clock = new Clock;
$source = Predetermined::of(
    static function(Suspend $suspend) use ($clock) {
        $halt = Halt::of($clock, $suspend);

        $halt(new Second(2));

        echo 'World !';
    },
    static function(Suspend $suspend) use ($clock) {
        $halt = Halt::of($clock, $suspend);

        $halt(new Second(1));

        echo 'Hello ';
    },
);

Forerunner::of($clock)(null, $source); // will print "Hello World !" in 2 seconds
```
