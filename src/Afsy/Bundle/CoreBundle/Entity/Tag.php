<?php

namespace Afsy\Bundle\CoreBundle\Entity;

use DoctrineExtensions\Taggable\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Afsy\Bundle\CoreBundle\Entity\TagRepository")
 */
class Tag extends BaseTag
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tagging", mappedBy="tag", fetch="EAGER")
     **/
    protected $tagging;

    protected $slug;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Returns tag slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets tag slug
     *
     * @return string
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
