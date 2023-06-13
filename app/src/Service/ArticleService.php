<?php
/**
 * Article service.
 */

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class ArticleService.
 */
class ArticleService
{
    private ArticleRepository $articleRepository;

    private PaginatorInterface $paginator;

    /**
     * Construct new article service object.
     *
     * @param ArticleRepository  $articleRepository Article repository
     * @param PaginatorInterface $paginator         Paginator interface
     */
    public function __construct(ArticleRepository $articleRepository, PaginatorInterface $paginator)
    {
        $this->articleRepository = $articleRepository;
        $this->paginator = $paginator;
    }

    /**
     * Save entity.
     *
     * @param Article $article Article entity
     */
    public function save(Article $article): void
    {
        $this->articleRepository->save($article);
    }

    /**
     * Delete entity.
     *
     * @param Article $article Article entity
     */
    public function delete(Article $article): void
    {
        $this->articleRepository->delete($article);
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->articleRepository->queryAll(),
            $page,
            ArticleRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
