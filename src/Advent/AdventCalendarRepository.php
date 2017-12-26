<?php

namespace Afsy\Advent;

use Afsy\Advent\Exception\AdventCalendarNotFoundException;

class AdventCalendarRepository
{
    private $calendars;

    public function __construct(array $calendars)
    {
        $this->calendars = $calendars;
    }

    public static function fromFile(string $file): self
    {
        if (!is_readable($file)) {
            throw new \InvalidArgumentException(sprintf('File %s is not readable or does not exist.', $file));
        }

        $calendars = include $file;
        if (!is_array($calendars)) {
            throw new \InvalidArgumentException(sprintf('File %s must contain an array of AdventCalendar instances.', $file));
        }

        return new self($calendars);
    }

    public function mostRecentYear(): int
    {
        return max(array_keys($this->calendars));
    }

    public function ofYear(int $year): AdventCalendar
    {
        $calendar = $this->calendars[$year] ?? null;

        if ($calendar instanceof AdventCalendar) {
            return $calendar;
        }

        if (!$calendar instanceof \Closure) {
            throw AdventCalendarNotFoundException::unsupportedYear($year);
        }

        $calendar = call_user_func($calendar);
        if (!$calendar instanceof AdventCalendar) {
            throw new \RuntimeException('Closure must generate an AdventCalendar instance.');
        }

        $this->calendars[$year] = $calendar;

        return $calendar;
    }
}