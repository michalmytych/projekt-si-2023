<?php
/**
 * Tag service interface.
 */

namespace App\Service\Interface;

use App\Entity\Tag;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
{
    /**
     * Save entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void;

    /**
     * Delete entity.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void;

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Find by id.
     *
     * @param int $id Tag id
     *
     * @return Tag|null Tag entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tag;
}
