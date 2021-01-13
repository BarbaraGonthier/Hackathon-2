<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $inseeCode;

    /**
     * @ORM\OneToMany(targetEntity=Buyer::class, mappedBy="city")
     */
    private $buyers;

    public function __construct()
    {
        $this->buyers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getInseeCode(): ?int
    {
        return $this->inseeCode;
    }

    public function setInseeCode(int $inseeCode): self
    {
        $this->inseeCode = $inseeCode;

        return $this;
    }

    /**
     * @return Collection|Buyer[]
     */
    public function getBuyers(): Collection
    {
        return $this->buyers;
    }

    public function addBuyer(Buyer $buyer): self
    {
        if (!$this->buyers->contains($buyer)) {
            $this->buyers[] = $buyer;
            $buyer->setCity($this);
        }

        return $this;
    }

    public function removeBuyer(Buyer $buyer): self
    {
        if ($this->buyers->removeElement($buyer)) {
            // set the owning side to null (unless already changed)
            if ($buyer->getCity() === $this) {
                $buyer->setCity(null);
            }
        }

        return $this;
    }
}
