<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit2
 *
 * @ORM\Table(name="produit2", indexes={@ORM\Index(name="IDX_BFF6AE8A58E019E5", columns={"category_p_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Produit2Repository")
 */
class Produit2
{
    /**
     * @var int
     *
     * @ORM\Column(name="idp2", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idp2;

    /**
     * @var string
     * @Assert\NotBlank(message="non vide")
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "doit etre >=3 ",
     *      maxMessage = "doit etre <=100" )
     * @ORM\Column(name="nom", type="string", length=250, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="auteur   :doit etre non vide")
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=250, nullable=false)
     */
    private $categorie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=250, nullable=false)
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="stock_produit", type="integer", nullable=false)
     */
    private $stockProduit;

    /**
     * @var string
     *
     * @ORM\Column(name="produit2", type="string", length=255, nullable=false)
     */
    private $produit2;

    /**
     * @var \CategoryP
     *
     * @ORM\ManyToOne(targetEntity="CategoryP")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_p_id", referencedColumnName="id")
     * })
     */
    private $categoryP;

    public function getIdp2(): ?int
    {
        return $this->idp2;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStockProduit(): ?int
    {
        return $this->stockProduit;
    }

    public function setStockProduit(int $stockProduit): self
    {
        $this->stockProduit = $stockProduit;

        return $this;
    }

    public function getProduit2(): ?string
    {
        return $this->produit2;
    }

    public function setProduit2(string $produit2): self
    {
        $this->produit2 = $produit2;

        return $this;
    }

    public function getCategoryP(): ?CategoryP
    {
        return $this->categoryP;
    }

    public function setCategoryP(?CategoryP $categoryP): self
    {
        $this->categoryP = $categoryP;

        return $this;
    }


}
