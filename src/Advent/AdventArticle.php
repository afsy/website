<?php

namespace Afsy\Advent;

class AdventArticle
{
    private $day;
    private $slug;
    private $title;

    public function __construct(int $day, string $title, string $slug)
    {
        $this->day = new AdventCalendarDay($day);
        $this->title = $title;
        $this->slug = $slug;
    }

    public function getDay(): AdventCalendarDay
    {
        return $this->day;
    }

    public function getSlug(): string
    {
        return sprintf('%02s-%s', $this->day->value(), $this->slug);
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}