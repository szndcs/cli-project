<?php

namespace App\Entity;

use App\Repository\InstagramSourcesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstagramSourcesRepository::class)]
class InstagramSources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    private ?int $fan_count = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getFan_count(): ?string
    {
        return $this->fan_count;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setFan_count(string $fan_count): static
    {
        $this->fan_count = $fan_count;

        return $this;
    }
}
