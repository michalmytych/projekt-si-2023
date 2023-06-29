<?php
/**
 * Category entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Gedmo\Mapping\Annotation as Gedmo;
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
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Name.
     */
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    /**
     * Slug.
     */
    #[ORM\Column(length: 512, unique: true)]
    #[Gedmo\Slug(fields: ['name', 'id'])]
    #[Assert\Length(min: 1, max: 512)]
    private ?string $slug = null;

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
}
