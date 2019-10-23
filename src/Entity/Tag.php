<?php

namespace App\Entity;

use FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Tag.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag extends BaseTag
{
    /**
     * @var int
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
}
