<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * User repository.
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * UserService constructor.
     *
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    /**
     * Pagination.
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function createPagination(int $page): PaginationInterface // @todo why unused
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
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Find user by ID.
     *
     * @param int $id
     *
     * @return User|null
     */
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->findOneById($id); // @todo check
    }
}
