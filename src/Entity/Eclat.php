<?php

namespace App\Entity;

use App\Repository\EclatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EclatRepository::class)
 */
class Eclat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $no;

    /**
     * @ORM\Column(type="float")
     */
    private $qt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNo(): ?string
    {
        return $this->no;
    }

    public function setNo(string $no): self
    {
        $this->no = $no;

        return $this;
    }

    public function getQt(): ?float
    {
        return $this->qt;
    }

    public function setQt(float $qt): self
    {
        $this->qt = $qt;

        return $this;
    }
}
