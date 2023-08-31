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
     * @param User $user User
     */
    public function save(User $user): void;

    /**
     * Find user by ID
     *
     * @param int $id Id
     *
     * @return User|null
     */
    public function findOneById(int $id): ?User;

    /**
     * Get latest admin user if exists.
     *
     * @throws NonUniqueResultException
     */
    public function getLatestAdminUser(): ?User;
}
