<?php

namespace Amp\Test\Future;

use Amp\Deferred;
use Amp\Future;
use PHPUnit\Framework\TestCase;
use function Amp\Future\first;

class FirstTest extends TestCase
{
    public function testSingleComplete(): void
    {
        self::assertSame(42, first([Future::complete(42)]));
    }

    public function testTwoComplete(): void
    {
        self::assertSame(1, Future\first([Future::complete(1), Future::complete(2)]));
    }

    public function testTwoFirstPending(): void
    {
        $deferred = new Deferred;

        self::assertSame(2, Future\first([$deferred->getFuture(), Future::complete(2)]));
    }

    public function testTwoFirstThrowing(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('foo');

        first([Future::error(new \Exception('foo')), Future::complete(2)]);
    }

    public function testTwoGeneratorThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('foo');

        first((static function () {
            yield Future::error(new \Exception('foo'));
            yield Future::complete(2);
        })());
    }
}