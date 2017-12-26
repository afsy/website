<?php

namespace Afsy\Advent;

class AdventCalendar implements \Countable, \IteratorAggregate
{
    private $year;

    /**
     * @var AdventArticle[]
     */
    private $articlesByDay = [];

    /**
     * @var AdventArticle[]
     */
    private $articlesBySlug = [];

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function add(AdventArticle $article): void
    {
        $day = $article->getDay();

        $this->articlesByDay[$day->value()] = $article;
        $this->articlesBySlug[$article->getSlug()] = $article;
    }

    public function allUntil(AdventCalendarDay $lastDay): self
    {
        $articles = array_filter(
            $this->articlesByDay,
            function (AdventArticle $article) use ($lastDay) {
                return $article->getDay()->beforeOrSame($lastDay);
            }
        );

        $calendar = new self($this->year);
        foreach ($articles as $article) {
            $calendar->add($article);
        }

        return $calendar;
    }

    public function bySlug(string $slug): ?AdventArticle
    {
        return $this->articlesBySlug[$slug] ?? null;
    }

    public function previousOf(AdventArticle $article): ?AdventArticle
    {
        $day = $article->getDay();

        if ($day->isFirst()) {
            return null;
        }

        $previousDay = $day->previous()->value();

        return $this->articlesByDay[$previousDay] ?? null;
    }

    public function nextOf(AdventArticle $article): ?AdventArticle
    {
        $day = $article->getDay();

        if ($day->isLast()) {
            return null;
        }

        $nextDay = $day->next()->value();

        return $this->articlesByDay[$nextDay] ?? null;
    }

    public function count(): int
    {
        return count($this->articlesByDay);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator(array_values($this->articlesByDay));
    }
}