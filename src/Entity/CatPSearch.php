<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class CatPSearch
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     */
    private $categoryP;


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