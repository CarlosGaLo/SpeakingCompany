<?php

namespace App\Entity;

use App\Repository\MailingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MailingRepository::class)
 */
class Mailing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $toIds;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastTryDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $finished;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalMails;

    /**
     * @ORM\Column(type="boolean")
     */
    private $processing;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getToIds(): ?string
    {
        return $this->toIds;
    }

    public function setToIds(string $toIds): self
    {
        $this->toIds = $toIds;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getLastTryDate(): ?\DateTimeInterface
    {
        return $this->lastTryDate;
    }

    public function setLastTryDate(?\DateTimeInterface $lastTryDate): self
    {
        $this->lastTryDate = $lastTryDate;

        return $this;
    }

    public function getFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getTotalMails(): ?int
    {
        return $this->totalMails;
    }

    public function setTotalMails(?int $totalMails): self
    {
        $this->totalMails = $totalMails;

        return $this;
    }
    
    public function getToCount(): int
    {
        $arrTo = []; $count = 0;
        if(trim($this->toIds)){
            $arrTo = explode(',', $this->toIds);        
            foreach ($arrTo as $myArrTo) {
                if(trim($myArrTo)){
                    $count++;
                }
            }
        }
        return $count;
    }

    public function getProcessing(): ?bool
    {
        return $this->processing;
    }

    public function setProcessing(bool $processing): self
    {
        $this->processing = $processing;

        return $this;
    }
    
    public function getToIdsArray(): array
    {
        $toIdsArray = array();
        $toIdsArrayRaw = explode(',', $this->toIds);
        
        for($i=0; $i<count($toIdsArrayRaw); $i++){
            if($toIdsArrayRaw[$i]){
                $toIdsArray[] = $toIdsArrayRaw[$i];
            }
        }
        
        return $toIdsArray;
    }
}
