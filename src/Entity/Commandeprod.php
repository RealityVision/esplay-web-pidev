<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandeprod
 *
 * @ORM\Table(name="commandeprod", indexes={@ORM\Index(name="fk_1", columns={"id_produit"})})
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 * @ORM\Entity
 */
class Commandeprod
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCommande;

    /**
     * @var int
     *
     * @ORM\Column(name="id_acheteur", type="integer", nullable=false)
     */


    private $idAcheteur;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Produit2
     *
     * @ORM\ManyToOne(targetEntity="Produit2")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="id_produit", referencedColumnName="idp2")
     * })
     */
    private $idProduit;

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getIdAcheteur(): ?int
    {
        return $this->idAcheteur;
    }

    public function setIdAcheteur(int $idAcheteur): self
    {
        $this->idAcheteur = $idAcheteur;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdProduit(): ?Produit2
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit2 $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }


}
