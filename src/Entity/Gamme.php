<?php

namespace App\Entity;

use App\Repository\GammeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GammeRepository::class)
 */
class Gamme
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
     * @ORM\Column(type="integer")
     */
    private $op;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $setup;

    /**
     * @ORM\Column(type="float")
     */
    private $run;

    /**
     * @ORM\Column(type="float")
     */
    private $qth;

    /**
     * @ORM\Column(type="float")
     */
    private $qtemoh;

    /**
     * @ORM\Column(type="integer")
     */
    private $qtmop;

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

    public function getOp(): ?int
    {
        return $this->op;
    }

    public function setOp(int $op): self
    {
        $this->op = $op;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSetup(): ?int
    {
        return $this->setup;
    }

    public function setSetup(int $setup): self
    {
        $this->setup = $setup;

        return $this;
    }

    public function getRun(): ?float
    {
        return $this->run;
    }

    public function setRun(float $run): self
    {
        $this->run = $run;

        return $this;
    }

    public function getQth(): ?float
    {
        return $this->qth;
    }

    public function setQth(float $qth): self
    {
        $this->qth = $qth;

        return $this;
    }

    public function getQtemoh(): ?float
    {
        return $this->qtemoh;
    }

    public function setQtemoh(float $qtemoh): self
    {
        $this->qtemoh = $qtemoh;

        return $this;
    }

    public function getQtemop(): ?int
    {
        return $this->qtmop;
    }

    public function setQtemop(int $qtmop): self
    {
        $this->qtmop = $qtmop;

        return $this;
    }
}
