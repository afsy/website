<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;

class AfsyExtractExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('afsy_extract', array($this, 'extract')),
        );
    }

    /**
     * Get the first <p></p> and return it.
     *
     * @param string $html
     *
     * @return string
     */
    public function extract($html = '')
    {
        $start = strpos($html, '<p>');
        $end = strpos($html, '</p>', $start);

        $content = substr($html, $start, $end - $start + 4);

        return preg_replace("/<img[^>]+\>/i", '', $content);
    }

    public function getName()
    {
        return 'afsy_extract';
    }
}
