<?php

namespace App\Markdown;


use Twig\Extra\Markdown\MarkdownInterface;

class CustomErusevMarkdown implements MarkdownInterface
{
    private $converter;

    public function __construct(CustomParsedown $converter = null)
    {
        $this->converter = $converter ?: new CustomParsedown();
    }

    public function convert(string $body): string
    {
        return $this->converter->text($body);
    }
}
