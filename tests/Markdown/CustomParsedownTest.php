<?php

namespace App\Tests\Markdown;

use App\Markdown\CustomParsedown;
use PHPUnit\Framework\TestCase;

class CustomParsedownTest extends TestCase
{
    public function testItGeneratesCorrectCodeInline()
    {
        $parser = new CustomParsedown();

        $markdown = 'Some `code`';
        $expected = '<p>Some <code class="language-none">code</code></p>';

        $this->assertEquals($expected, $parser->text($markdown));
    }

    public function testItGeneratesCorrectNoLanguageCodeBlock()
    {
        $parser = new CustomParsedown();

        $markdown = <<<MARKDOWN
        ```
        code
        ```
        MARKDOWN;
        $expected = '<pre><code class="language-none">code</code></pre>';

        $this->assertEquals($expected, $parser->text($markdown));
    }

    public function testItDoesNotOverrideAlreadySetLanguage()
    {
        $parser = new CustomParsedown();

        $markdown = <<<MARKDOWN
        ```php
        code
        ```
        MARKDOWN;
        $expected = '<pre><code class="language-php">code</code></pre>';

        $this->assertEquals($expected, $parser->text($markdown));
    }
}
