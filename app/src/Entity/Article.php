<?php
/**
 * Article entity.
 */

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Article.
 */
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\Table(name: 'articles')]
class Article
{
    /**
     * Status draft.
     *
     * @const STATUS_DRAFT
     */
    public const STATUS_DRAFT = 0;

    /**
     * Status published.
     *
     * @const STATUS_PUBLISHED
     */
    public const STATUS_PUBLISHED = 1;

    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $title = null;

    /**
     * Content.
     */
    #[ORM\Column(type: Types::TEXT, length: 5000)]
    #[Assert\Length(min: 10, max: 5000)]
    private ?string $content = null;

    /**
     * Status.
     */
    #[ORM\Column(type: Types::SMALLINT, options: ['default' => self::STATUS_DRAFT])]
    private ?int $status = null;

    /**
     * Created at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $createdAt;

    /**
     * Updated at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'update')]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $updatedAt;

    /**
     * Category.
     */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Tags.
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: File::class, fetch: 'EXTRA_LAZY')]
    private Collection $files;

    /**
     * Construct new Article object.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    /**
     * Get article id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get article title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set article title.
     *
     * @param string $title Title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get article content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set article content.
     *
     * @param string $content Content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for created at.
     *
     * @return DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param DateTimeImmutable|null $createdAt Created at
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for updated at.
     *
     * @return DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updated at.
     *
     * @param DateTimeImmutable|null $updatedAt Updated at
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get category to which article is related.
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set category to which article is related.
     *
     * @param Category|null $category Category entity
     *
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get tags related to article.
     *
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to tags related to article.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove tag from related tags.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Get status.
     *
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param int $status Status value
     *
     * @return $this
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns true if article is published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return Article::STATUS_PUBLISHED === $this->getStatus();
    }

    /**
     * Get related files.
     *
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    /**
     * Add file to related files.
     *
     * @param File $file File entity
     *
     * @return $this
     */
    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }

        return $this;
    }
}
