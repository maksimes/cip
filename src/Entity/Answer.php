<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserAnswer", mappedBy="answer", orphanRemoval=true)
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
            $useranswer->setAnswer($this);
        }

        return $this;
    }

    public function removeUseranswer(UserAnswer $useranswer): self
    {
        if ($this->useranswers->contains($useranswer)) {
            $this->useranswers->removeElement($useranswer);
            // set the owning side to null (unless already changed)
            if ($useranswer->getAnswer() === $this) {
                $useranswer->setAnswer(null);
            }
        }

        return $this;
    }
}
