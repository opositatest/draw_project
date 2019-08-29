<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Lottery", mappedBy="users")
     */
    private $lotteries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lottery", mappedBy="ganador")
     */
    private $lotteries_ganados;

    public function __construct()
    {
        $this->lotteries = new ArrayCollection();
        $this->lotteries_ganados = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername()
    {
        return $this->nombre;
    }

    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        //Not needed with Bcrypt
    }

    public function eraseCredentials()
    {
        //Not needed in this case.
    }

    /**
     * @return Collection|Lottery[]
     */
    public function getLotteries(): Collection
    {
        return $this->lotteries;
    }

    public function addLottery(Lottery $lottery): self
    {
        if (!$this->lotteries->contains($lottery)) {
            $this->lotteries[] = $lottery;
            $lottery->addUser($this);
        }

        return $this;
    }

    public function removeLottery(Lottery $lottery): self
    {
        if ($this->lotteries->contains($lottery)) {
            $this->lotteries->removeElement($lottery);
            $lottery->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Lottery[]
     */
    public function getLotteriesGanados(): Collection
    {
        return $this->lotteries_ganados;
    }

    public function addLotteriesGanado(Lottery $lotteriesGanado): self
    {
        if (!$this->lotteries_ganados->contains($lotteriesGanado)) {
            $this->lotteries_ganados[] = $lotteriesGanado;
            $lotteriesGanado->setGanador($this);
        }

        return $this;
    }

    public function removeLotteriesGanado(Lottery $lotteriesGanado): self
    {
        if ($this->lotteries_ganados->contains($lotteriesGanado)) {
            $this->lotteries_ganados->removeElement($lotteriesGanado);
            // set the owning side to null (unless already changed)
            if ($lotteriesGanado->getGanador() === $this) {
                $lotteriesGanado->setGanador(null);
            }
        }

        return $this;
    }
}
