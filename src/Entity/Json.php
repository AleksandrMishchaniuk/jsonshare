<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JsonRepository")
 */
class Json
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $text = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hash", mappedBy="json", orphanRemoval=true)
     */
    private $hashes;

    public function __construct()
    {
        $this->hashes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?array
    {
        return $this->text;
    }

    public function setText(array $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Hash[]
     */
    public function getHashes(): Collection
    {
        return $this->hashes;
    }

    public function addHash(Hash $hash): self
    {
        if (!$this->hashes->contains($hash)) {
            $this->hashes[] = $hash;
            $hash->setJson($this);
        }

        return $this;
    }

    public function removeHash(Hash $hash): self
    {
        if ($this->hashes->contains($hash)) {
            $this->hashes->removeElement($hash);
            // set the owning side to null (unless already changed)
            if ($hash->getJson() === $this) {
                $hash->setJson(null);
            }
        }

        return $this;
    }
}
