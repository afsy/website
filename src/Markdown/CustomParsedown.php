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

    protected function blockFencedCode($Line)
    {
        $tagInfo = parent::blockFencedCode($Line);

        if (isset($tagInfo['element']['text']['name']) && !isset($tagInfo['element']['text']['attributes'])) {
            $tagInfo['element']['text']['attributes'] = ['class' => 'language-none'];
        }

        return $tagInfo;
    }
}
