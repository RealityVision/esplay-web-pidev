<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint as Assert;


/**
 * Game
 *
 * @ORM\Table(name="game", indexes={@ORM\Index(name="cat", columns={"category"})})
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Game
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_game", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idGame;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=30, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250, nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=false)
     */
    private $size;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_g", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateG = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_g", type="string", length=255, nullable=true)
     */
    private $imageG;

    /**
     * @var File|null
     *
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="imageG")

     */
    private $imageFile;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float", precision=10, scale=0, nullable=false)
     */
    private $rate = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="rate_nbr", type="integer", nullable=true, options={"default"="1"})
     */
    private $rateNbr = 1;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="category_id", onDelete="CASCADE")
     * })
     */
    private $category;

    public function getIdGame(): ?int
    {
        return $this->idGame;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getDateG(): ?\DateTime
    {
        return $this->dateG;
    }

    public function setDateG(\DateTime $dateG): self
    {
        $this->dateG = $dateG;

        return $this;
    }

    public function getImageG(): ?string
    {
        return $this->imageG;
    }

    public function setImageG(?string $imageG): self
    {
        $this->imageG = $imageG;

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

    public function getRateNbr(): ?int
    {
        return $this->rateNbr;
    }

    public function setRateNbr(?int $rateNbr): self
    {
        $this->rateNbr = $rateNbr;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Game
     */
    public function setImageFile(?File $imageFile): Game
    {

        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->dateG = new \DateTime('now');
        }

        return $this;
    }
}
