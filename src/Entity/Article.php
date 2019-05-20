<?php

namespace Afsy\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use DoctrineExtensions\Taggable\Taggable;

/**
 * Article model.
 *
 * @author Xavier LACOT <xlacot@jolicode.com>
 * @ORM\Entity(repositoryClass="Afsy\Entity\ArticleRepository")
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
     */
    protected $body;

    /**
     * @var string
     * @ORM\Column(type="text", name="markdown_body")
     * @Assert\NotBlank()
     */
    protected $markdownBody;

    /**
     * @var string
     * @ORM\Column(type="string", length=2)
     * @Assert\Choice(choices = {"fr", "en"}, message = "Choose a valid language.")
     */
    protected $language = 'fr';

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="published_at")
     * @Assert\DateTime()
     */
    protected $publishedAt;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true, name="event_url")
     */
    protected $eventUrl;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="event_date", nullable=true)
     * @Assert\DateTime()
     */
    protected $eventDate;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $address;

    /**
     * @var string
     * @ORM\Column(type="text")
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
     * @ORM\Column(type="boolean", name="is_published")
     */
    protected $isPublished;

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
     *
     * @var string $tagsList
     */
    protected $tagsList = false;

    public function __construct()
    {
        $this->authors      = new ArrayCollection();
        $this->tags         = new ArrayCollection();
        $this->publishedAt  = new \DateTime();
        $this->map          = '<img src="http://maps.googleapis.com/maps/api/staticmap?zoom=15&size=470x260&maptype=roadmap&markers=color:green|48.8588589,2.3470599&sensor=false" alt="" />';
    }

    public function __toString()
    {
        return $this->title ?: 'n/a';
    }

    public function isEventStarted()
    {
        if (!$this->eventDate) {
            return false;
        }

        return new \Datetime() > $this->eventDate;
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
     * Set event date
     *
     * @param  \DateTime  $date
     * @return Article
     */
    public function setEventDate(\DateTime $date)
    {
        $this->eventDate = $date;

        return $this;
    }

    /**
     * Returns event date
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set eventUrl
     *
     * @param  string  $eventUrl
     * @return Article
     */
    public function setEventUrl($eventUrl)
    {
        $this->eventUrl = $eventUrl;

        return $this;
    }

    /**
     * Get eventUrl
     *
     * @return string
     */
    public function getEventUrl()
    {
        return $this->eventUrl;
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
     * @param  string    $body
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
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set city
     *
     * @param  string    $city
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
     * @return string
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
     * @param  string    $markdownBody
     * @return Article
     */
    public function setMarkdownBody($markdownBody)
    {
        $this->markdownBody = $markdownBody;

        return $this;
    }

    /**
     * Get markdownBody
     *
     * @return string
     */
    public function getMarkdownBody()
    {
        return $this->markdownBody;
    }

    public function getShortBody()
    {
        return strip_tags(substr($this->getBody(), 0, 250));
    }

    /**
     * Set publishedAt
     *
     * @param  \DateTime $publishedAt
     * @return Article
     */
    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set isPublished
     *
     * @param  boolean $isPublished
     * @return Article
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->isPublished;
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
     * @param ArrayCollection $authors
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
        if (!$this->tagsList) {
            $this->tagsList = array();
            foreach ($this->getTags() as $tag) {
                $this->tagsList[] = $tag->getName();
            }
            $this->tagsList = implode(', ', $this->tagsList);
        }

        // force lowercase
        return strtolower($this->tagsList);
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
     * @param string $tagsList
     */
    public function setTagsList($tagsList)
    {
        $this->tagsList = $tagsList;
    }


    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}
