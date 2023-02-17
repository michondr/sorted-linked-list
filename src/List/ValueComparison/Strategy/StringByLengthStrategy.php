<?php

declare(strict_types=1);

namespace List\ValueComparison\Strategy;

use List\ValueComparison\ComparisonOutcomeEnum;
use List\ValueComparison\ValueComparisonStrategyInterface;

use function _PHPStan_35ce48cb5\RingCentral\Psr7\str;
use function PHPStan\dumpType;

class StringByLengthStrategy implements ValueComparisonStrategyInterface
{
    /**
     * @param string $first
     * @param string $second
     */
    public function compareValues(mixed $first, mixed $second): ComparisonOutcomeEnum
    {
        if (!is_string($first) || !is_string($second)) {
            throw new \InvalidArgumentException(
                sprintf('cannot compare "%s" with "%s", as one of them is not a string', $first, $second)
            );
        }

        return match (mb_strlen($first) <=> mb_strlen($second)) {
            -1 => ComparisonOutcomeEnum::LESS,
            0 => ComparisonOutcomeEnum::EQUALS,
            1 => ComparisonOutcomeEnum::MORE,
        };
    }
}
