<?php
/**
 * User service interface.
 */

namespace App\Service\Interface;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Pagination.
     *
     * @param int $page Page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save record.
     *
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * Find user by ID.
     *
     * @param int $id User id
     *
     * @return User|null
     */
    public function findOneById(int $id): ?User;

    /**
     * Get latest admin user if exists.
     *
     * @return User|null
     *
     * @throws NonUniqueResultException
     */
    public function getLatestAdminUser(): ?User;
}
