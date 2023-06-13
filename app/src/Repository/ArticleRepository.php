<?php
/**
 * Article repository.
 */

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Construct new article repository object.
     *
     * @param ManagerRegistry $registry
     *
     * @noinspection PhpMultipleClassDeclarationsInspection
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Save record in a database.
     *
     * @param Article $article Article entity
     */
    public function save(Article $article): void
    {
        $this->_em->persist($article);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Article $article Article entity
     */
    public function delete(Article $article): void
    {
        $this->_em->remove($article);
        $this->_em->flush();
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this
            ->getOrCreateQueryBuilder()
            ->orderBy('article.createdAt', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('article');
    }
}
