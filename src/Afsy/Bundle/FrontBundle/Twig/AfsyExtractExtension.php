<?php

namespace Afsy\Bundle\FrontBundle\Twig;

class AfsyExtractExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('afsy_extract', array($this, 'extract')),
        );
    }

  /**
   * Get the first <p></p> and return it
   *
   * @param string $html
   * @return string
   */
  public function extract($html = "")
    {
        $start = strpos($html, '<p>');
        $end   = strpos($html, '</p>', $start);

        return substr($html, $start, $end-$start+4);
    }

    public function getName()
    {
        return 'afsy_extract';
    }
}
