<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Interface\UserServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * UserService constructor.
     *
     * @param UserRepository     $userRepository User repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    /**
     * Pagination.
     *
     * @param int $page Page
     *
     * @return PaginationInterface Pagination
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save record.
     *
     * @param User $user User
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Find user by ID.
     *
     * @param int $id Id
     *
     * @return User|null User|null
     */
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->findOneById($id);
    }

    /**
     * Get latest admin user if exists.
     *
     * @return User|null User|null
     *
     * @throws NonUniqueResultException
     */
    public function getLatestAdminUser(): ?User
    {
        return $this->userRepository->getLatestAdminUser();
    }
}
