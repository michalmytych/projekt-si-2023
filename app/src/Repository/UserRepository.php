<?php
/**
 * User repository.
 */

namespace App\Repository;

use App\Entity\User;
use App\Entity\Enum\UserRole;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    /**
     * Items per page.
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     *
     * @noinspection PhpMultipleClassDeclarationsInspection
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param UserInterface $user
     * @param string        $newHashedPassword
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Query all users.
     *
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this
            ->getOrCreateQueryBuilder()
            ->orderBy('user.id', 'ASC');
    }

    /**
     * Query all users with 'latest-first' ordering.
     *
     * @return QueryBuilder
     */
    public function queryAllLatest(): QueryBuilder
    {
        return $this
            ->getOrCreateQueryBuilder()
            ->orderBy('user.id', 'DESC');
    }

    /**
     * Find single user by email.
     *
     * @param mixed $email Email
     *
     * @return User|null
     */
    public function findOneByEmail(mixed $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Find single user by id.
     *
     * @param mixed $id User id
     *
     * @return User|null
     */
    public function findOneById(mixed $id): ?User
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Save user.
     *
     * @param User $user User
     */
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Get latest admin user if exists.
     *
     * @return User|null
     *
     * @throws NonUniqueResultException
     */
    public function getLatestAdminUser(): ?User
    {
        return $this
            ->queryAllLatest()
            ->where('user.roles LIKE :role')
            ->setParameter('role', '%'.UserRole::ROLE_ADMIN->value.'%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     *
     * @noinspection PhpSameParameterValueInspection
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('user');
    }
}
