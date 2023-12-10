<?php

namespace App\Entity;

use App\Repository\CsvDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CsvDataRepository::class)]
class CsvData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $customer_id = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(length: 35)]
    private ?string $phone = null;

    #[ORM\Column(length: 50)]
    private ?string $ipaddress = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $num_region = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $ip_region = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customerId): static
    {
        $this->customer_id = $customerId;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIpaddress(): ?string
    {
        return $this->ipaddress;
    }

    public function setIpaddress(string $ipaddress): static
    {
        $this->ipaddress = $ipaddress;

        return $this;
    }

    public function getNumRegion(): ?string
    {
        return $this->num_region;
    }

    public function setNumRegion(?string $num_region): static
    {
        $this->num_region = $num_region;

        return $this;
    }

    public function getIpRegion(): ?string
    {
        return $this->ip_region;
    }

    public function setIpRegion(?string $ip_region): static
    {
        $this->ip_region = $ip_region;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
