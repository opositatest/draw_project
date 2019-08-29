<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 * @Vich\Uploadable
 */
class Result
{
    /**
     * Many Results have One Poll.
     *
     * @ORM\ManyToOne(targetEntity="Poll", inversedBy="results")
     * @ORM\JoinColumn(name="poll_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $poll;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $explanation;

    /**
     * @ORM\Column(type="integer")
     */
    private $minVal;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxVal;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the poll.
     *
     * @Vich\UploadableField(mapping="poll_imgs", fileNameProperty="image")
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

    public function __toString()
    {
        return $this->text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function setExplanation(string $explanation): self
    {
        $this->explanation = $explanation;

        return $this;
    }

    public function getMinVal(): ?int
    {
        return $this->minVal;
    }

    public function setMinVal(int $minVal): self
    {
        $this->minVal = $minVal;

        return $this;
    }

    public function getMaxVal(): ?int
    {
        return $this->maxVal;
    }

    public function setMaxVal(int $maxVal): self
    {
        $this->maxVal = $maxVal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param mixed $poll
     */
    public function setPoll($poll): void
    {
        $this->poll = $poll;
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
