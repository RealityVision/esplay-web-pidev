<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devupload
 *
 * @ORM\Table(name="devupload")
 * @ORM\Entity
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


}
