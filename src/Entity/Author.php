<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 * @UniqueEntity(
 *  fields={"surname", "name", "midname"},
 *  message="validators.author.exists"
 * )
 */
class Author
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validation.author.name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validation.author.surname")
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="validation.author.midname")
     */
    private $midname;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Book", mappedBy="authors")
     */
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getMidname(): ?string
    {
        return $this->midname;
    }

    public function setMidname(string $midname): self
    {
        $this->midname = $midname;

        return $this;
    }

    // Special getter for full name with initials 
    public function getFullname(): ?string
    {
        return $this->surname . ' ' . 
            mb_substr($this->name, 0, 1) . '. ' . 
            mb_substr($this->midname, 0, 1) . '.';
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
            $book->removeAuthor($this);
        }

        return $this;
    }
}
