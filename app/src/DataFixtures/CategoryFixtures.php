<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures implements OrderedFixtureInterface
{
    /**
     * Get order of fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * Load fixture data.
     *
     * @return void
     */
    protected function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $category = new Category();
            $category->setName($this->faker->unique()->word);

            $this->manager->persist($category);
        }

        $this->manager->flush();
    }
}
