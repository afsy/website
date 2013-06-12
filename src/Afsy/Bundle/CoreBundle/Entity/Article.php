<?php

namespace Afsy\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use DoctrineExtensions\Taggable\Taggable;

/**
 * Article model.
 *
 * @author Xavier LACOT <xlacot@jolicode.com>
 * @ORM\Entity(repositoryClass="Afsy\Bundle\CoreBundle\Entity\ArticleRepository")
 */
class Article implements Taggable
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
    protected $title;

    /**
     * @var string
     * @Gedmo\Slug(separator="-", unique=true, fields={"title"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $body;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $markdown_body;

    /**
     * @var string
     * @ORM\Column(type="string", length=2)
     * @Assert\Choice(choices = {"fr", "en"}, message = "Choose a valid language.")
     */
    protected $language = 'fr';

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $published_at;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $event_url;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $map;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    protected $city;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $is_published;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Author", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinTable(name="Article_Author")
     * @Assert\Valid
     */
    protected $authors;

    protected $tags;

    /**
     * The same as tags but as a comma separated list
     * @var string $tags_list
     */
    protected $tags_list = false;

    public function __construct()
    {
        $this->authors      = new ArrayCollection();
        $this->tags         = new ArrayCollection();
        $this->published_at = new \DateTime();
    }

    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param  string  $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param  string  $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set event_url
     *
     * @param  string  $eventUrl
     * @return Article
     */
    public function setEventUrl($eventUrl)
    {
        $this->event_url = $eventUrl;

        return $this;
    }

    /**
     * Get event_url
     *
     * @return string
     */
    public function getEventUrl()
    {
        return $this->event_url;
    }

    /**
     * Set map html code
     *
     * @param  string  $map
     * @return Article
     */
    public function setMap($map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map html code
     *
     * @return string
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set body
     *
     * @param  text    $body
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return text
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set city
     *
     * @param  text    $city
     * @return Article
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return text
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set language
     *
     * @param  string  $language
     * @return Article
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set markdownBody
     *
     * @param  text    $markdownBody
     * @return Article
     */
    public function setMarkdownBody($markdown_body)
    {
        $this->markdown_body = $markdown_body;

        return $this;
    }

    /**
     * Get markdownBody
     *
     * @return text
     */
    public function getMarkdownBody()
    {
        return $this->markdown_body;
    }

    public function getShortBody()
    {
        return strip_tags( substr($this->getBody(), 0, 250) );
    }

    /**
     * Set published_at
     *
     * @param  datetime $publishedAt
     * @return Article
     */
    public function setPublishedAt($publishedAt)
    {
        $this->published_at = $publishedAt;

        return $this;
    }

    /**
     * Get published_at
     *
     * @return datetime
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * Set is_published
     *
     * @param  boolean $isPublished
     * @return Article
     */
    public function setIsPublished($isPublished)
    {
        $this->is_published = $isPublished;

        return $this;
    }

    /**
     * Get is_published
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * Add authors
     *
     * @param  Author  $authors
     * @return Article
     */
    public function addAuthor(Author $authors)
    {
        $this->authors[] = $authors;

        return $this;
    }

    /**
     * Get authors
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set authors
     *
     * @return Article
     */
    public function setAuthors(ArrayCollection $authors)
    {
        foreach ($authors as $author) {
            $author->addArticle($this);
        }

        $this->authors = $authors;

        return $this;
    }

    /**
     * Remove author
     *
     * @param  Author  $author
     * @return Article
     */
    public function removeAuthor(Author $author)
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        if (is_array($this->tags)) {
            $this->tags = new ArrayCollection($this->tags);
        }

        return $this->tags;
    }

    /**
     * Used to ease the Form usage of taggable
     * @return string
     */
    public function getTagsList()
    {
        if ($this->tags_list === false) {
            $this->tags_list = array();
            foreach ($this->getTags() as $tag) {
                $this->tags_list[] = $tag->getName();
            }
            $this->tags_list = implode(', ', $this->tags_list);
        }

        // force lowercase
        return strtolower($this->tags_list);
    }

    public function getTaggableType()
    {
        return 'article_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * @param string $tags_list
     */
    public function setTagsList($tags_list)
    {
        $this->tags_list = $tags_list;
    }
}
