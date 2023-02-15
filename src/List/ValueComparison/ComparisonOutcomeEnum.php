<?php

namespace List\ValueComparison;

enum ComparisonOutcomeEnum
{
    case LESS;
    case SAME;
    case MORE;

    public function isLessOrEqual(): bool
    {
        return $this === self::LESS || $this === self::SAME;
    }

    public function isMore(): bool
    {
        return $this === self::MORE;
    }

    public function isSame(): bool
    {
        return $this === self::SAME;
    }
}
