<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

//CREATION DE LA TABLE TAG
/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="le champ doit etre rempli")
     */

    private $color;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\article" , mappedBy="tag")
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return
     */
    public function getArticles()
    {
        return $this->articles;
    }


}
