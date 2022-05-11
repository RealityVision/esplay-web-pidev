<?php

namespace App\Entity;

use App\Repository\RecomendedgRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RecomendedgRepository::class)
 */
class Recomendedg
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("recomendedg")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recomendedg")
     * @Groups("posts:read")
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_category", referencedColumnName="category_id")
     * })
     * @Groups("recomendedg")
     * @Groups("posts:read")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recomendedg")
     * @Groups("posts:read")
     */
    private $platform;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recomendedg")
     * @Groups("posts:read")
     */
    private $url;

    /**
     * @ORM\Column(type="float")
     * @Groups("recomendedg")
     * @Groups("posts:read")
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="idrec", cascade={ "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id" ,onDelete="CASCADE")
     */
    private $commentaire;


    public function getId(): ?int
    {
        return $this->id;
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


    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getReponses(): ?Collection
    {
        return $this->commentaire;
    }

    public function addReponse(Commentaire $reponse): self
    {
        if (!$this->commentaire->contains($reponse)) {
            $this->commentaire[] = $reponse;
            $reponse->setIdrec($this);
        }

        return $this;
    }

    public function removeReponse(Commentaire $reponse): self
    {
        if ($this->commentaire->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getIdrec() === $this) {
                $reponse->setIdrec(null);
            }
        }

        return $this;
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



}
