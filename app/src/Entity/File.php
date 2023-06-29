<?php
/**
 * File entity
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class File.
 */
#[ORM\Entity(repositoryClass: FileRepository::class)]
#[ORM\Table(name: 'files')]
#[ORM\UniqueConstraint(name: 'uq_files_path', columns: ['path'])]
class File
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
     * File path.
     *
     * @var string|null
     */
    #[ORM\Column(length: 191)]
    #[Assert\Length(min: 1, max: 191)]
    private ?string $path = null;

    /**
     * Related articles.
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'files')]
    private Collection $articles;

    /**
     * Construct new File object.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * File id getter.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * File path getter.
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * File path setter.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Related articles getter.
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
     * @param Article $article
     *
     * @return $this
     */
    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }

        return $this;
    }

    /**
     * Remove article from related articles.
     *
     * @param Article $article
     *
     * @return $this
     */
    public function removeArticle(Article $article): static
    {
        $this->articles->removeElement($article);

        return $this;
    }
}
