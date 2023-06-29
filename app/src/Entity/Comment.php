<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Header.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $header = null;

    /**
     * Content.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $content = null;

    /**
     * Article.
     */
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * Get comment id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get comment header.
     */
    public function getHeader(): ?string
    {
        return $this->header;
    }

    /**
     * Set comment header.
     *
     * @param string $header Header
     *
     * @return $this
     */
    public function setHeader(string $header): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get comment content.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set comment content.
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
     * Get article to which comment is related.
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * Set article to which comment is related.
     *
     * @param Article|null $article Article
     *
     * @return $this
     */
    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Author getter.
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Author getter.
     *
     * @return $this
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
