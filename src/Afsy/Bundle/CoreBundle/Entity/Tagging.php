<?php

namespace Afsy\Bundle\CoreBundle\Entity;

use DoctrineExtensions\Taggable\Entity\Tagging as BaseTagging;
use Doctrine\ORM\Mapping as ORM;

/**
 * Afsy\Bundle\CoreBundle\Entity\Tagging
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="tagging_idx", columns={"tag_id", "resource_type", "resource_id"})})
 * @ORM\Entity
 */
class Tagging extends BaseTagging
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
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="tagging")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     **/
    protected $tag;
}
