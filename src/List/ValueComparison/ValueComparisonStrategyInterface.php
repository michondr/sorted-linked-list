<?php

declare(strict_types=1);

namespace List\ValueComparison;

interface ValueComparisonStrategyInterface
{
    public function compareValues(mixed $first, mixed $second): ComparisonOutcomeEnum;
}
