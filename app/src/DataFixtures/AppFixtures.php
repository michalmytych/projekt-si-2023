<?php
/**
 * App fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class AppFixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * Load app fixtures.
     *
     * @param ObjectManager $manager Object manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
