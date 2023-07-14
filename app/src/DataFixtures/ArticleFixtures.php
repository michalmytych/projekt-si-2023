<?php
/**
 * Article fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Article;
use App\Repository\CategoryRepository;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class ArticleFixtures.
 */
class ArticleFixtures extends AbstractBaseFixtures implements OrderedFixtureInterface
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Construct new article fixtures class.
     *
     * @param CategoryRepository $categoryRepository Category repository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get order of fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }

    /**
     * Load fixture data.
     */
    protected function loadData(): void
    {
        $categories = $this->categoryRepository->findAll();

        foreach ($categories as $category) {
            for ($i = 0; $i < 10; ++$i) {
                $article = new Article();
                $article->setCategory($category);
                $article->setTitle($this->faker->unique()->sentence);
                $article->setContent($this->faker->realText(516));
                $article->setStatus($this->faker->randomElement([
                    Article::STATUS_DRAFT,
                    Article::STATUS_PUBLISHED,
                ]));
                $article->setCreatedAt(
                    \DateTimeImmutable::createFromMutable(
                        $this->faker->dateTimeBetween('-100 days', '-1 days')
                    )
                );
                $article->setUpdatedAt(
                    \DateTimeImmutable::createFromMutable(
                        $this->faker->dateTimeBetween('-100 days', '-1 days')
                    )
                );

                $this->manager->persist($article);
            }
        }

        $this->manager->flush();
    }
}
