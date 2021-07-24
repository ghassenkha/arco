<?php

namespace App\Entity;

use App\Repository\BesoinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BesoinRepository::class)
 */
class Besoin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $no;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s3;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s4;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s5;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s6;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s7;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s8;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s9;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s10;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s11;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s12;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s13;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s14;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s15;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $s16;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $somme;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getS1(): ?int
    {
        return $this->s1;
    }

    public function setS1(?int $s1): self
    {
        $this->s1 = $s1;

        return $this;
    }

    public function getS2(): ?int
    {
        return $this->s2;
    }

    public function setS2(?int $s2): self
    {
        $this->s2 = $s2;

        return $this;
    }

    public function getS3(): ?int
    {
        return $this->s3;
    }

    public function setS3(?int $s3): self
    {
        $this->s3 = $s3;

        return $this;
    }

    public function getS4(): ?int
    {
        return $this->s4;
    }

    public function setS4(?int $s4): self
    {
        $this->s4 = $s4;

        return $this;
    }

    public function getS5(): ?int
    {
        return $this->s5;
    }

    public function setS5(?int $s5): self
    {
        $this->s5 = $s5;

        return $this;
    }

    public function getS6(): ?int
    {
        return $this->s6;
    }

    public function setS6(?int $s6): self
    {
        $this->s6 = $s6;

        return $this;
    }

    public function getS7(): ?int
    {
        return $this->s7;
    }

    public function setS7(?int $s7): self
    {
        $this->s7 = $s7;

        return $this;
    }

    public function getS8(): ?int
    {
        return $this->s8;
    }

    public function setS8(?int $s8): self
    {
        $this->s8 = $s8;

        return $this;
    }

    public function getS9(): ?int
    {
        return $this->s9;
    }

    public function setS9(?int $s9): self
    {
        $this->s9 = $s9;

        return $this;
    }

    public function getS10(): ?int
    {
        return $this->s10;
    }

    public function setS10(?int $s10): self
    {
        $this->s10 = $s10;

        return $this;
    }

    public function getS11(): ?int
    {
        return $this->s11;
    }

    public function setS11(?int $s11): self
    {
        $this->s11 = $s11;

        return $this;
    }

    public function getS12(): ?int
    {
        return $this->s12;
    }

    public function setS12(?int $s12): self
    {
        $this->s12 = $s12;

        return $this;
    }

    public function getS13(): ?int
    {
        return $this->s13;
    }

    public function setS13(?int $s13): self
    {
        $this->s13 = $s13;

        return $this;
    }

    public function getS14(): ?int
    {
        return $this->s14;
    }

    public function setS14(?int $s14): self
    {
        $this->s14 = $s14;

        return $this;
    }

    public function getS15(): ?int
    {
        return $this->s15;
    }

    public function setS15(?int $s15): self
    {
        $this->s15 = $s15;

        return $this;
    }

    public function getS16(): ?int
    {
        return $this->s16;
    }

    public function setS16(?int $s16): self
    {
        $this->s16 = $s16;

        return $this;
    }

    public function getSomme(): ?int
    {
        return $this->somme;
    }


    public function setSomme(?int $somme): self
    {
        $this->somme = $somme;

        return $this;
    }


}
