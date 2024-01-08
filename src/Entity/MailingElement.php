<?php

namespace App\Entity;

use App\Repository\MailingElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MailingElementRepository::class)
 */
class MailingElement
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname2;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastCourse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $origin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mailNb;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastMail;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $testMail;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\ManyToOne(targetEntity=MailingElementRol::class)
     */
    private $rolType;   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getSurname2(): ?string
    {
        return $this->surname2;
    }

    public function setSurname2(?string $surname2): self
    {
        $this->surname2 = $surname2;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLastCourse(): ?string
    {
        return $this->lastCourse;
    }

    public function setLastCourse(?string $lastCourse): self
    {
        $this->lastCourse = $lastCourse;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getMailNb(): ?int
    {
        return $this->mailNb;
    }

    public function setMailNb(?int $mailNb): self
    {
        $this->mailNb = $mailNb;

        return $this;
    }

    public function getLastMail(): ?\DateTimeInterface
    {
        return $this->lastMail;
    }

    public function setLastMail(?\DateTimeInterface $lastMail): self
    {
        $this->lastMail = $lastMail;

        return $this;
    }
    
    public function replaceText($text){
        $text = str_replace('[|fullname|]', ($this->name . " " . $this->surname . " " . $this->surname2), $text);
        $text = str_replace('[|name|]', $this->name, $text);
        $text = str_replace('[|surname1|]', $this->surname, $text);
        $text = str_replace('[|surname2|]', $this->surname2, $text);
        $text = str_replace('[|lastCourse|]', $this->lastCourse, $text);
        
        return $text;
    }

    public function getTestMail(): ?bool
    {
        return $this->testMail;
    }

    public function setTestMail(?bool $testMail): self
    {
        $this->testMail = $testMail;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getRolType(): ?MailingElementRol
    {
        return $this->rolType;
    }

    public function setRolType(?MailingElementRol $rolType): self
    {
        $this->rolType = $rolType;

        return $this;
    }
}
