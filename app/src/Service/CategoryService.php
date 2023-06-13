<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Repository\CategoryRepository;

/**
 * Class CategoryService.
 */
class CategoryService
{
    private CategoryRepository $categoryRepository;

    /**
     * Construct new category service object.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
}
