<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Blog
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlogRepository")
 */
class Blog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="author", type="string", length=50)
     */
    private $author;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="article", type="text")
     */
    private $article;

    /**
     * @var string
     * @ORM\Column(name="image_article", type="string", length=255, nullable=true)
     */

    private $imageArticle;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="article")
     */
    private $comments;

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
     * @return Blog
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
     * Set author
     *
     * @param string $author
     *
     * @return Blog
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

    /**
     * Set article
     *
     * @param string $article
     *
     * @return Blog
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set imageArticle
     *
     * @param string $imageArticle
     *
     * @return Blog
     */
    public function setImageArticle($imageArticle)
    {
        $this->imageArticle = $imageArticle;

        return $this;
    }

    /**
     * Get imageArticle
     *
     * @return string
     */
    public function getImageArticle()
    {
        return $this->imageArticle;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }



}

