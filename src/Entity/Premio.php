<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PremioRepository")
 * @Vich\Uploadable
 */
class Premio
{
    /**
     * One Premio has Many Sorteos.
     *
     * @ORM\OneToMany(targetEntity="Sorteo", mappedBy="premio")
     */
    private $sorteos;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagen;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the encuesta.
     *
     * @Vich\UploadableField(mapping="encuesta_imgs", fileNameProperty="imagen")
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

    public function __toString()
    {
        return $this->title;
    }

    //
    //

    /**
     * @return mixed
     */
    public function getSorteos()
    {
        return $this->sorteos;
    }

    /**
     * @param mixed $sorteos
     */
    public function setSorteos($sorteos): void
    {
        $this->sorteos = $sorteos;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

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
