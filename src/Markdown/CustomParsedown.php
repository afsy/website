<?php

namespace App\Markdown;


class CustomParsedown extends \Parsedown
{
    protected function inlineCode($Excerpt)
    {
        $tagInfo = parent::inlineCode($Excerpt);

        $tagInfo['element']['attributes'] = ['class' => 'language-none'];

        return $tagInfo;
    }
}
