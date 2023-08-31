<?php
/**
 * Article service interface.
 */

namespace App\Service\Interface;

use App\Entity\Article;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface ArticleServiceInterface.
 */
interface ArticleServiceInterface
{
    /**
     * Save entity.
     *
     * @param Article $article Article
     */
    public function save(Article $article): void;

    /**
     * Delete entity.
     *
     * @param Article $article Article
     */
    public function delete(Article $article): void;

    /**
     * Get paginated list.
     *
     * @param int                $page    Page
     * @param array              $filters Filters
     * @param UserInterface|null $user    User
     *
     * @return PaginationInterface Paginator
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, array $filters = [], UserInterface $user = null): PaginationInterface;

    /**
     * Find one article by id.
     *
     * @param int $id Id
     *
     * @return Article Article
     */
    public function findOneById(int $id): Article;
}
