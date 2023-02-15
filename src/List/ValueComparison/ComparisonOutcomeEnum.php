<?php

namespace List\ValueComparison;

enum ComparisonOutcomeEnum
{
    case LESS;
    case EQUALS;
    case MORE;

    public function isLessOrEqual(): bool
    {
        return $this === self::LESS || $this === self::EQUALS;
    }

    public function isMore(): bool
    {
        return $this === self::MORE;
    }

    public function isSame(): bool
    {
        return $this === self::EQUALS;
    }
}
