<?php

namespace Afsy\Advent\Exception;

class AdventCalendarNotFoundException extends \DomainException
{
    public function __construct(string $message = '', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function unsupportedYear(int $year): self
    {
        return new self(sprintf('No advent calendar configured in %s.', $year));
    }
}