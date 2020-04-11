<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HashRepository")
 */
class Hash
{
    const ACCESS_LEVEL_READ = 1;
    const ACCESS_LEVEL_EDIT = 2;

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
        $this->access_level = in_array($access_level, [self::ACCESS_LEVEL_READ, self::ACCESS_LEVEL_EDIT]) ?
            $access_level : self::ACCESS_LEVEL_READ;

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

    public function canRead(): bool
    {
        return $this->getAccessLevel() >= self::ACCESS_LEVEL_READ;
    }

    public function canEdit(): bool
    {
        return $this->getAccessLevel() >= self::ACCESS_LEVEL_EDIT;
    }

    public static function generateHash()
    {
        return md5(time() . uniqid() . rand(1, 1000));
    }
}
