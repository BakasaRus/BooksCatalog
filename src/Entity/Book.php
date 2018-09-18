<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @UniqueEntity(
 *      "isbn", 
 *      message="validators.book.exists_isbn"
 * )
 * @UniqueEntity(
 *      fields={"title", "publicationYear"}, 
 *      message="validators.book.exists_title_pub"
 * )
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validators.book.title")
     */
    private $title;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank(message="validators.book.pub_year")
     */
    private $publicationYear;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validators.book.isbn")
     * @Assert\Isbn(
     *     type="isbn13",
     *     message="validators.book.isbn_invalid"
     * )
     */
    private $isbn;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="validators.book.pages")
     */
    private $pageCount;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="books")
     */
    private $authors;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpeg"},
     *     mimeTypesMessage="validators.book.cover_formats"
     * )
     */
    private $cover;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getPublicationYear(): ?int
    {
        return $this->publicationYear;
    }

    public function setPublicationYear(int $publicationYear): self
    {
        $this->publicationYear = $publicationYear;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = str_replace('-', '', $isbn);

        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }

    public function getCover(): ?string
    {
        if ($this->cover == '')
            return 'placeholder.jpg';
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }
}
