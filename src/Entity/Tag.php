<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="tags")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=Press::class, mappedBy="tags")
     */
    private $presses;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, mappedBy="tags")
     */
    private $books;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->presses = new ArrayCollection();
        $this->books = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addTag($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|Press[]
     */
    public function getPresses(): Collection
    {
        return $this->presses;
    }

    public function addPress(Press $press): self
    {
        if (!$this->presses->contains($press)) {
            $this->presses[] = $press;
            $press->addTag($this);
        }

        return $this;
    }

    public function removePress(Press $press): self
    {
        if ($this->presses->removeElement($press)) {
            $press->removeTag($this);
        }

        return $this;
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
            $book->addTag($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeTag($this);
        }

        return $this;
    }
}
