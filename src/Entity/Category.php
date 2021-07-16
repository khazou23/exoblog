<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

//CREATION DE LA TABLE CATEGORY
/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
// DECLARATION DES PROPRIETES (intitulé de chaque colonne)
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champ doit être rempli")
     * @Assert\Length(
     *     min=3,
     *     max=255 ,
     *     minMessage="Il faut au minimum un titre de 3 lettres",
     *     maxMessage="Le nombre de caractères autorisés est dépassé")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\article" , mappedBy="category")
     */
    private $articles;
    //creation d une methode contructeur pour pour stocker plusieurs elements dans un array
    //une variable ne pouvant contenir qu un element
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

//DECLARATION DES GETTERS ET SETTERS
//A NOTER : pas de setter pour l id car pas necessaire
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return
     */
    public function getArticles()
    {
        return $this->articles;
    }

    public function setCategory(?Category $category)
    {
    }

}
