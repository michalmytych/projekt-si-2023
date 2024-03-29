<?php
/**
 * Article service.
 */

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Interface\ArticleServiceInterface;
use App\Service\Interface\CategoryServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ArticleService.
 */
class ArticleService implements ArticleServiceInterface
{
    /**
     * Article repository.
     */
    private ArticleRepository $articleRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Tag service.
     */
    private TagService $tagService;

    /**
     * Construct new article service object.
     *
     * @param ArticleRepository        $articleRepository Article repository
     * @param PaginatorInterface       $paginator         Paginator
     * @param CategoryServiceInterface $categoryService   Category service
     * @param TagService               $tagService        Tag service
     */
    public function __construct(ArticleRepository $articleRepository, PaginatorInterface $paginator, CategoryServiceInterface $categoryService, TagService $tagService)
    {
        $this->articleRepository = $articleRepository;
        $this->paginator = $paginator;
        $this->tagService = $tagService;
        $this->categoryService = $categoryService;
    }

    /**
     * Save entity.
     *
     * @param Article $article Article
     */
    public function save(Article $article): void
    {
        $this->articleRepository->save($article);
    }

    /**
     * Delete entity.
     *
     * @param Article $article Article
     */
    public function delete(Article $article): void
    {
        $this->articleRepository->delete($article);
    }

    /**
     * Get paginated list.
     *
     * @param int                $page    Page
     * @param array              $filters Filters
     * @param UserInterface|null $user    User
     *
     * @return PaginationInterface Pagination
     *
     * @throws NonUniqueResultException NonUniqueResultException
     */
    public function getPaginatedList(int $page, array $filters = [], UserInterface $user = null): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->articleRepository->queryAll($filters, $user),
            $page,
            ArticleRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find one article by id.
     *
     * @param int $id Id
     *
     * @return Article Article
     */
    public function findOneById(int $id): Article
    {
        return $this->articleRepository->find($id);
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array $filters Filters
     *
     * @return array Filters
     *
     * @throws NonUniqueResultException NonUniqueResultException
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
