<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 */
class Section
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
    private $sect;

    /**
     * @ORM\Column(type="integer")
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

    public function getSect(): ?string
    {
        return $this->sect;
    }

    public function setSect(string $sect): self
    {
        $this->sect = $sect;

        return $this;
    }

    public function getEff(): ?int
    {
        return $this->eff;
    }

    public function setEff(int $eff): self
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
