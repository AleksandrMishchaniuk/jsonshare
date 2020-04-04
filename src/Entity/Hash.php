<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HashRepository")
 */
class Hash
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
    private $hash;

    /**
     * @ORM\Column(type="integer")
     */
    private $access_level;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Json", inversedBy="hashes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $json;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getAccessLevel(): ?int
    {
        return $this->access_level;
    }

    public function setAccessLevel(int $access_level): self
    {
        $this->access_level = $access_level;

        return $this;
    }

    public function getJson(): ?Json
    {
        return $this->json;
    }

    public function setJson(?Json $json): self
    {
        $this->json = $json;

        return $this;
    }
}
