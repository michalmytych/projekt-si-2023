<?php
/**
 * File entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FileRepository;
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
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * File path.
     */
    #[ORM\Column(length: 191)]
    #[Assert\Length(min: 1, max: 191)]
    private ?string $path = null;

    /**
     * File id getter.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * File path getter.
     *
     * @return string|null Path
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * File path setter.
     *
     * @param string $path Path
     *
     * @return File File
     */
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
