<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="AppBundle\Repositories\TagRepository")
 */
class Tag
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
     *
     * @Assert\NotBlank(message = "Blank tagname!.")
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Tagname can not be less than {{ limit }}!",
     *      maxMessage = "Tagname can not be more than {{ limit }}!"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $tagname;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Article", mappedBy="tags", cascade={"persist"}, orphanRemoval=true)
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
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
    public function getTagname()
    {
        return $this->tagname;
    }

    /**
     * @param $tagname
     * @return $this
     */
    public function setTagname($tagname)
    {
        $this->tagname = $tagname;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param $articles
     * @return $this
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;

        return $this;
    }

    /**
     * @param Article $article
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
    }
    /**
     *
     * @param Article $article
     */
    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

}