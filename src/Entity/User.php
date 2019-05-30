<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserAnswer", mappedBy="user", orphanRemoval=true)
     */
    private $useranswers;

    public function __construct()
    {
        $this->useranswers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return Collection|UserAnswer[]
     */
    public function getUseranswers(): Collection
    {
        return $this->useranswers;
    }

    public function addUseranswer(UserAnswer $useranswer): self
    {
        if (!$this->useranswers->contains($useranswer)) {
            $this->useranswers[] = $useranswer;
            $useranswer->setUser($this);
        }

        return $this;
    }

    public function removeUseranswer(UserAnswer $useranswer): self
    {
        if ($this->useranswers->contains($useranswer)) {
            $this->useranswers->removeElement($useranswer);
            // set the owning side to null (unless already changed)
            if ($useranswer->getUser() === $this) {
                $useranswer->setUser(null);
            }
        }

        return $this;
    }

}
