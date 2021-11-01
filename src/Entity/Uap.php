<?php

namespace App\Entity;

use App\Repository\UapRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UapRepository::class)
 */
class Uap
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
    private $poste;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $section;

    /**
     * @ORM\Column(type="float")
     */
    private $eff;

    /**
     * @ORM\Column(type="float")
     */
    private $ineff;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getEff(): ?float
    {
        return $this->eff;
    }

    public function setEff(float $eff): self
    {
        $this->eff = $eff;

        return $this;
    }

    public function getIneff(): ?float
    {
        return $this->ineff;
    }

    public function setIneff(float $ineff): self
    {
        $this->ineff = $ineff;

        return $this;
    }
}
