<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exceptions\GanadorNotSettedException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LotteryRepository")
 * @Vich\Uploadable
 */
class Lottery
{
    /**
     * Many Lotteries have One Prize.
     *
     * @ORM\ManyToOne(targetEntity="Prize", inversedBy="lotteries")
     * @ORM\JoinColumn(name="prize_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $prize;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="lotteries")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lotteries_ganados")
     */
    private $ganador;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the poll.
     *
     * @Vich\UploadableField(mapping="poll_imgs", fileNameProperty="img")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    //
    //

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * @param mixed $prize
     */
    public function setPrize($prize): void
    {
        $this->prize = $prize;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getGanador(): ?User
    {
        return $this->ganador;
    }

    /**
     * @param null|User $ganador
     *
     * @throws GanadorNotSettedException
     *
     * @return Lottery
     */
    public function setGanador(?User $ganador): self
    {
        $hoy = new \DateTimeImmutable();

        if ($this->getFecha() > $hoy) {
            throw new GanadorNotSettedException('No se ha podido añadir el ganador al lottery '.$this->id.'. Fecha de lottery > actual.');
        }
        $this->ganador = $ganador;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $img = null): void
    {
        $this->imageFile = $img;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($img) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
