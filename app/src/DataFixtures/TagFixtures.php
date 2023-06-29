<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures implements OrderedFixtureInterface
{
    /**
     * Get order of fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 4;
    }

    /**
     * Load fixture data data.
     *
     * @return void
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->name);

            $this->manager->persist($tag);
        }

        $this->manager->flush();
    }
}
