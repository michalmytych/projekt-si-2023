<?php
/**
 * Tag entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Tag.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
#[UniqueEntity('name')]
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
     * Get tag id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get tag name.
     *
     * @return string|null Name
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
     * @return Tag
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
