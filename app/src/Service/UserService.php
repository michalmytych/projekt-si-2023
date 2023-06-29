<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class UserService.
 */
class UserService
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
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    /**
     * Pagination.
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
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Find user by ID.
     *
     * @param int $id User id
     */
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->findOneById($id);
    }

    /**
     * Get latest admin user if exists.
     *
     * @throws NonUniqueResultException
     */
    public function getLatestAdminUser(): ?User
    {
        return $this->userRepository->getLatestAdminUser();
    }
}
