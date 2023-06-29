<?php
/**
 * Category service.
 */

namespace App\Service;

use LogicException;
use App\Entity\Category;
use Doctrine\ORM\NoResultException;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Interface\CategoryServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Article repository.
     */
    private ArticleRepository $articleRepository;

    /**
     * Construct new category service object.
     *
     * @param CategoryRepository $categoryRepository Category repository
     * @param PaginatorInterface $paginator          Paginator interface
     * @param ArticleRepository  $articleRepository  Article repository
     */
    public function __construct(CategoryRepository $categoryRepository, PaginatorInterface $paginator, ArticleRepository $articleRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->paginator = $paginator;
    }

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        if (!$this->canBeDeleted($category)) {
            throw new LogicException('Cannot delete category if it has articles attached.');
        }

        $this->categoryRepository->delete($category);
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
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->articleRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}
