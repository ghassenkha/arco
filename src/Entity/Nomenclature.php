<?php

namespace App\Entity;

use App\Repository\NomenclatureRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=NomenclatureRepository::class)
 */
class Nomenclature
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
    private $BOM_No;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $No;

    /**
     * @ORM\Column(type="float")
     */
    private $qt_per;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $SystReap;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBOMNo(): ?string
    {
        return $this->BOM_No;
    }

    public function setBOMNo(string $BOM_No): self
    {
        $this->BOM_No = $BOM_No;

        return $this;
    }

    public function getNo(): ?string
    {
        return $this->No;
    }

    public function setNo(string $No): self
    {
        $this->No = $No;

        return $this;
    }

    public function getQtPer(): ?float
    {
        return $this->qt_per;
    }

    public function setQtPer(float $qt_per): self
    {
        $this->qt_per = $qt_per;

        return $this;
    }

    public function getSystReap(): ?string
    {
        return $this->SystReap;
    }

    public function setSystReap(string $SystReap): self
    {
        $this->SystReap = $SystReap;

        return $this;
    }
}
