<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity()
 */
class Comment
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
     * @Assert\NotBlank(message = "Blank text!.")
     * @ORM\Column(type="text")
     */
    private $commentText;

    /**
     * @var int
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 10,
     *      minMessage = "Mark can not be less than {{ limit }}!",
     *      maxMessage = "Mark can not be more than {{ limit }}!"
     * )
     * @ORM\Column(type="integer")
     */
    private $commentMark;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Article", inversedBy="comments")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Author", inversedBy="comments")
     */
    private $author;

    /**
     * @Gedmo\Slug(fields={"commentMark", "createdAt"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;

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
    public function getCommentText()
    {
        return $this->commentText;
    }

    /**
     * @param $commentText
     * @return $this
     */
    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommentMark()
    {
        return $this->commentMark;
    }

    /**
     * @param $commentMark
     * @return $this
     */
    public function setCommentMark($commentMark)
    {
        $this->commentMark = $commentMark;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param $article
     * @return $this
     */
    public function setArticle($article)
    {
        $this->article = $article;

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