<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\ArticleRepository;
use Symfony\Component\Validator\Constraints as Assert;

//CREATION DE LA TABLE ARTICLE
/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class article
{
// DECLARATION DES PROPRIETES (intitulé de chaque colonne)
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type ="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre doit être renseigné")
     * @Assert\Length(
     *     min=6,
     *     max=200 ,
     *     minMessage="Il faut au minimum un titre de 3 lettres",
     *     maxMessage="Le nombre de caractères autorisés est dépassé")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le résumé de l'article doit être renseigné")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $createAt;

    /**
     * @ORM\Column (type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category" , inversedBy="articles")
     * @Assert\NotBlank (message="Le type de catégorie doit être renseigné")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag", inversedBy="articles")
     * @Assert\NotBlank(message="Le type de tag doit être renseigné")
     */
    private $tag;

//DECLARATION DES GETTERS ET SETTERS
//A NOTER : pas de setter pour l id car pas necessaire
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param mixed $createAt
     */
    public function setCreateAt($createAt): void
    {
        $this->createAt = $createAt;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param mixed $isPublished
     */
    public function setIsPublished($isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag): void
    {
        $this->tag = $tag;
    }


}
?>