<?php

namespace App\Entity;

use App\Repository\IPsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IPsRepository::class)]
class IPs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private $user_ip;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $weather;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIp(): ?string
    {
        return $this->user_ip;
    }

    public function setUserIp(?string $user_ip): self
    {
            
        $this->user_ip = $user_ip;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(?string $weather): self
    {
        $this->weather = $weather;

        return $this;
    }
    
}
