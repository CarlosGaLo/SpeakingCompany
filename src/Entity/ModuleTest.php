<?php

namespace App\Entity;

use App\Repository\ModuleTestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleTestRepository::class)
 */
class ModuleTest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="smallint")
     */
    private $timeMinutes;

    /**
     * @ORM\Column(type="smallint")
     */
    private $passPercent;

    /**
     * @ORM\Column(type="smallint")
     */
    private $tryChances;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="moduleTests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $module;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTimeMinutes(): ?int
    {
        return $this->timeMinutes;
    }

    public function setTimeMinutes(int $timeMinutes): self
    {
        $this->timeMinutes = $timeMinutes;

        return $this;
    }

    public function getPassPercent(): ?int
    {
        return $this->passPercent;
    }

    public function setPassPercent(int $passPercent): self
    {
        $this->passPercent = $passPercent;

        return $this;
    }

    public function getTryChances(): ?int
    {
        return $this->tryChances;
    }

    public function setTryChances(int $tryChances): self
    {
        $this->tryChances = $tryChances;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }
}
