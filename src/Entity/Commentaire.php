<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Text;

    /**
     * @ORM\Column(type="float")
     */
    private $rate;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Recomendedg")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idrec", referencedColumnName="id")
     * })
     */
    private $idrec;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id_user")
     * })
     */
    private $user;

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
    public function setUser($user): void
    {
        $this->user = $user;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdrec()
    {
        return $this->idrec;
    }

    /**
     * @param mixed $idrec
     */
    public function setIdrec($idrec): void
    {
        $this->idrec = $idrec;
    }


}
