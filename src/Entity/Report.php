<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report", indexes={@ORM\Index(name="fk_sender", columns={"id_sender"}), @ORM\Index(name="fk_u", columns={"id_user"}), @ORM\Index(name="fk_m", columns={"id_message"})})
 * @ORM\Entity
 */
class Report
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
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255, nullable=false)
     */
    private $reason;

    /**
     * @var \Chat
     *
     * @ORM\ManyToOne(targetEntity="Chat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_message", referencedColumnName="id_message")
     * })
     */
    private $idMessage;



    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sender", referencedColumnName="id_user")
     * }) 
     */
    private $idSender;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getIdMessage(): ?Chat
    {
        return $this->idMessage;
    }

    public function setIdMessage(?Chat $idMessage): self
    {
        $this->idMessage = $idMessage;

        return $this;
    }



    public function getIdSender(): ?User
    {
        return $this->idSender;
    }

    public function setIdSender(?User $idSender): self
    {
        $this->idSender = $idSender;

        return $this;
    }
}
