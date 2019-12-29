<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")K
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="alias_url", type="string", length=100, nullable=false, unique=true)
     */
    private $alias_url;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false, unique=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published", type="datetime", nullable=false, unique=true)
     */
    private $published;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true, unique=false)
     */
    private $image_url;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255, nullable=true, unique=false)
     */
    private $author;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
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
     * Set aliasUrl
     *
     * @param string $aliasUrl
     *
     * @return Post
     */
    public function setAliasUrl($aliasUrl)
    {
        $this->alias_url = $aliasUrl;

        return $this;
    }

    /**
     * Get aliasUrl
     *
     * @return string
     */
    public function getAliasUrl()
    {
        return $this->alias_url;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param \DateTime $published
     *
     * @return Post
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return \DateTime
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return Post
     */
    public function setImageUrl($imageUrl)
    {
        $this->image_url = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }
}

