<?php
/**
 * Tag entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
class Tag
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Name.
     */
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $name = null;

    /**
     * Articles.
     */
    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'tags')]
    private Collection $articles;

    /**
     * Construct new Tag object.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * Get tag id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get tag name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set tag name.
     *
     * @param string $name Name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get related articles collection.
     *
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * Add article to related articles.
     *
     * @param Article $article Article
     *
     * @return $this
     */
    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addTag($this);
        }

        return $this;
    }

    /**
     * Remove article from related articles.
     *
     * @param Article $article Article
     *
     * @return $this
     */
    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeTag($this);
        }

        return $this;
    }
}
