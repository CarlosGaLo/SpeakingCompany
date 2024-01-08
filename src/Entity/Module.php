<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=ModuleVideo::class, mappedBy="module", cascade={"merge", "persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $moduleVideos;

    /**
     * @ORM\OneToMany(targetEntity=ModuleText::class, mappedBy="module", cascade={"merge", "persist", "remove"}, orphanRemoval=true)
     */
    private $moduleTexts;

    /**
     * @ORM\OneToMany(targetEntity=ModuleTest::class, mappedBy="module", orphanRemoval=true)
     */
    private $moduleTests;

    /**
     * @ORM\OneToMany(targetEntity=ModuleForumPost::class, mappedBy="module")
     */
    private $moduleForumPosts;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $headerImage;

    public function __construct()
    {
        $this->moduleVideos = new ArrayCollection();
        $this->moduleTexts = new ArrayCollection();
        $this->moduleTests = new ArrayCollection();
        $this->moduleForumPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCourse(): ?course
    {
        return $this->course;
    }

    public function setCourse(?course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection|ModuleVideo[]
     */
    public function getModuleVideos(): Collection
    {
        return $this->moduleVideos;
    }

    public function addModuleVideo(ModuleVideo $moduleVideo): self
    {
        if (!$this->moduleVideos->contains($moduleVideo)) {
            $this->moduleVideos[] = $moduleVideo;
            $moduleVideo->setModule($this);
        }

        return $this;
    }

    public function removeModuleVideo(ModuleVideo $moduleVideo): self
    {
        if ($this->moduleVideos->contains($moduleVideo)) {
            $this->moduleVideos->removeElement($moduleVideo);
            // set the owning side to null (unless already changed)
            if ($moduleVideo->getModule() === $this) {
                $moduleVideo->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ModuleText[]
     */
    public function getModuleTexts(): Collection
    {
        return $this->moduleTexts;
    }

    public function addModuleText(ModuleText $moduleText): self
    {
        if (!$this->moduleTexts->contains($moduleText)) {
            $this->moduleTexts[] = $moduleText;
            $moduleText->setModule($this);
        }

        return $this;
    }

    public function removeModuleText(ModuleText $moduleText): self
    {
        if ($this->moduleTexts->contains($moduleText)) {
            $this->moduleTexts->removeElement($moduleText);
            // set the owning side to null (unless already changed)
            if ($moduleText->getModule() === $this) {
                $moduleText->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ModuleTest[]
     */
    public function getModuleTests(): Collection
    {
        return $this->moduleTests;
    }

    public function addModuleTest(ModuleTest $moduleTest): self
    {
        if (!$this->moduleTests->contains($moduleTest)) {
            $this->moduleTests[] = $moduleTest;
            $moduleTest->setModule($this);
        }

        return $this;
    }

    public function removeModuleTest(ModuleTest $moduleTest): self
    {
        if ($this->moduleTests->contains($moduleTest)) {
            $this->moduleTests->removeElement($moduleTest);
            // set the owning side to null (unless already changed)
            if ($moduleTest->getModule() === $this) {
                $moduleTest->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ModuleForumPost[]
     */
    public function getModuleForumPosts(): Collection
    {
        return $this->moduleForumPosts;
    }

    public function addModuleForumPost(ModuleForumPost $moduleForumPost): self
    {
        if (!$this->moduleForumPosts->contains($moduleForumPost)) {
            $this->moduleForumPosts[] = $moduleForumPost;
            $moduleForumPost->setModule($this);
        }

        return $this;
    }

    public function removeModuleForumPost(ModuleForumPost $moduleForumPost): self
    {
        if ($this->moduleForumPosts->contains($moduleForumPost)) {
            $this->moduleForumPosts->removeElement($moduleForumPost);
            // set the owning side to null (unless already changed)
            if ($moduleForumPost->getModule() === $this) {
                $moduleForumPost->setModule(null);
            }
        }

        return $this;
    }
    
    public function getHeaderImage(): ?string
    {
        return $this->headerImage;
    }

    public function setHeaderImage(?string $headerImage): self
    {
        $this->headerImage = $headerImage;

        return $this;
    }
}
