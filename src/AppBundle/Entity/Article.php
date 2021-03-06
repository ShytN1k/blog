<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Article
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="AppBundle\Repositories\ArticleRepository")
 */
class Article
{
    use TimestampableEntity;

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
     *
     * @Assert\NotBlank(message = "Blank name!.")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Blank text!.")
     * @ORM\Column(type="text")
     */
    private $articleText;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Author", inversedBy="articles")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="articles", cascade={"persist"}, orphanRemoval=true)
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="article", cascade={"persist"}, orphanRemoval=true)
     */
    private $comments;

    /**
     * @var float
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      minMessage = "Mark can not be less than {{ limit }}!",
     *      maxMessage = "Mark can not be more than {{ limit }}!"
     * )
     * @ORM\Column(type="float")
     */
    private $mark = 0;

    /**
     * @Gedmo\Slug(fields={"createdAt", "name"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleText()
    {
        return $this->articleText;
    }

    /**
     * @param $articleText
     * @return $this
     */
    public function setArticleText($articleText)
    {
        $this->articleText = $articleText;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);
    }
    /**
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
    }
    /**
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @return int
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param $mark
     * @return $this
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}