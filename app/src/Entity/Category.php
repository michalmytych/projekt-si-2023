<?php
/**
 * Category entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
class Category
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Name.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    /**
     * Slug.
     *
     * @var string|null
     */
    #[ORM\Column(length: 512, unique: true)]
    #[Gedmo\Slug(fields: ['name', 'id'])]
    #[Assert\Length(min: 1, max: 512)]
    private ?string $slug = null;

    /**
     * Articles.
     *
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Article::class, orphanRemoval: true)]
    private Collection $articles;

    /**
     * Construct new category object.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * Get category id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get category name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set category name.
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
     * Get category slug.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Get related articles.
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
            $article->setCategory($this);
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
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
