<?php

namespace Afsy\Bundle\AdminBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MarkdownType extends TextareaType
{
    public function getName()
    {
        return 'markdown';
    }
}
