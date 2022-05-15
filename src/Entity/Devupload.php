<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Devupload
 *
 * @ORM\Table(name="devupload")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Devupload
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="file", type="blob", length=65535, nullable=true)
     */
    private $file;

    /**
     * @var string|null
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;


    /**
     * @var File|null
     *
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="filename")

     */
    private $gameFile;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateup", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    public $dateUp = 'CURRENT_TIMESTAMP';




    public function getDateUp(): ?\DateTime
    {
        return $this->dateUp;
    }


    public function setDateUp(\DateTime $dateUp): Devupload
    {
        $this->dateUp = $dateUp;
        return $this;
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $file_name): self
    {
        $this->filename = $file_name;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getGameFile(): ?File
    {
        return $this->gameFile;
    }

    /**
     * @param File|null $gameFile
     * @return Devupload
     */
    public function setGameFile(?File $gameFile): Devupload
    {
        $this->gameFile = $gameFile;
        if ($this->gameFile instanceof UploadedFile) {
            $this->dateUp = new \DateTime('now');
        }
        return $this;
    }


}
