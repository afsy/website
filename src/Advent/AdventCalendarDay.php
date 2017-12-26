<?php

namespace Afsy\Advent;

final class AdventCalendarDay
{
    private const FIRST_DAY = 1;
    private const LAST_DAY = 24;

    private $value;

    public function __construct(int $value)
    {
        if ($value < self::FIRST_DAY || $value > self::LAST_DAY) {
            throw new \InvalidArgumentException(sprintf('$value must be an integer between %u and %u.', self::FIRST_DAY, self::LAST_DAY));
        }

        $this->value = $value;
    }

    public static function lastDay(): self
    {
        return new self(self::LAST_DAY);
    }

    public function isFirst(): bool
    {
        return self::FIRST_DAY === $this->value;
    }

    public function isLast(): bool
    {
        return self::LAST_DAY === $this->value;
    }

    public function previous(): self
    {
        return new self($this->value - 1);
    }

    public function next(): self
    {
        return new self($this->value + 1);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function beforeOrSame(self $other): bool
    {
        return $this->value <= $other->value;
    }
}