<?php

namespace List\ValueComparison;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ComparisonOutcomeEnumTest extends TestCase
{
    public function testIsLessOrEqual(): void
    {
        $more = ComparisonOutcomeEnum::MORE;
        $same = ComparisonOutcomeEnum::SAME;
        $less = ComparisonOutcomeEnum::LESS;

        Assert::assertFalse($more->isLessOrEqual());
        Assert::assertTrue($same->isLessOrEqual());
        Assert::assertTrue($less->isLessOrEqual());
    }

    public function testIsSame(): void
    {
        $more = ComparisonOutcomeEnum::MORE;
        $same = ComparisonOutcomeEnum::SAME;
        $less = ComparisonOutcomeEnum::LESS;

        Assert::assertFalse($more->isSame());
        Assert::assertTrue($same->isSame());
        Assert::assertFalse($less->isSame());
    }

    public function testIsMore(): void
    {
        $more = ComparisonOutcomeEnum::MORE;
        $same = ComparisonOutcomeEnum::SAME;
        $less = ComparisonOutcomeEnum::LESS;

        Assert::assertTrue($more->isMore());
        Assert::assertFalse($same->isMore());
        Assert::assertFalse($less->isMore());
    }
}
