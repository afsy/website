<?php

namespace Afsy\Bundle\CoreBundle\Entity;

use JMS\SecurityExtraBundle\Security\Util\String;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author model.
 *
 * @ORM\Entity()
 */
class Author implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var boolean
     *
     * Author is enable to log into admin
     *
     * @ORM\Column(type="boolean", name="is_enabled")
     * @Assert\NotBlank()
     */
    protected $isEnabled = false;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="authors")
     * @ORM\OrderBy({"published_at" = "DESC"})
     */
    protected $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Author
     */

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set username
     *
     * @param  string $username
     * @return Author
     */

    public function setUsername($username)
    {
        if (null == $this->name) {
            $this->setName($username);
        }
        $this->username = $username;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */

    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param  string $city
     * @return Author
     */

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */

    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add article
     *
     * @param  Article $article
     * @return Author
     */

    public function addArticle(Article $article)
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }

        return $this;
    }

    /**
     * Get articles
     *
     * @return Doctrine\Common\Collections\Collection
     */

    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Get published articles
     *
     * @return Doctrine\Common\Collections\Collection
     */

    public function getPublishedArticles()
    {
        return array_filter($this->getArticles()->toArray(),
                array('\Afsy\Bundle\CoreBundle\Entity\ArticleRepository',
                        'isPublished'));
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return User
     */

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set is_enabled
     *
     * @param  boolean $isEnabled
     * @return Article
     */

    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get is_enabled
     *
     * @return boolean
     */

    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @see http://fr.gravatar.com/site/implement/hash/
     * @return string
     */

    public function getGravatarHash()
    {
        return md5(strtolower(trim($this->getEmail())));
    }

    public function getRoles()
    {
        return array('ROLE_USER', 'ROLE_SONATA_ADMIN');
    }

    public function getPassword()
    {
        return '';
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }
}
