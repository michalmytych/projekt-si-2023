<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Enum\UserRole;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures implements OrderedFixtureInterface
{
    /**
     * Get order in which fixture should be loaded.
     *
     * @return int Order
     */
    public function getOrder(): int
    {
        return 0;
    }

    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data.
     *
     * @noinspection PhpConditionAlreadyCheckedInspection
     */
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        for ($i = 1; $i <= 5; ++$i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setNickname(sprintf('user%d', $i));
            $user->setRoles([UserRole::ROLE_USER->value]);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'userpassword'
                )
            );

            $this->manager->persist($user);
        }

        for ($i = 1; $i <= 2; ++$i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setNickname(sprintf('admin%d', $i));
            $user->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'adminpassword'
                )
            );

            $this->manager->persist($user);
        }

        $this->manager->flush();
    }
}
