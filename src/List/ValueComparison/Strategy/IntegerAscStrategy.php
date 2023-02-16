<?php

namespace List\ValueComparison\Strategy;

use List\ValueComparison\ComparisonOutcomeEnum;
use List\ValueComparison\ValueComparisonStrategyInterface;

class IntegerAscStrategy implements ValueComparisonStrategyInterface
{
    /**
     * @param int $first
     * @param int $second
     */
    public function compareValues(mixed $first, mixed $second): ComparisonOutcomeEnum
    {
        if (!is_int($first) || !is_int($second)) {
            throw new \InvalidArgumentException(
                sprintf('cannot compare "%s" with "%s", as one of them is not an integer', $first, $second)
            );
        }
        return match ($first <=> $second) {
            -1 => ComparisonOutcomeEnum::LESS,
            0 => ComparisonOutcomeEnum::EQUALS,
            1 => ComparisonOutcomeEnum::MORE,
        };
    }
}
