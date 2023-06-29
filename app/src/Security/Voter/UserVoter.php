<?php
/**
 * User security voter.
 */

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class UserVoter.
 */
class UserVoter extends Voter
{
    /**
     * Permissions.
     *
     * @param string $attribute Voter attribute
     * @param mixed  $subject   Voter subject
     *
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['VIEW', 'EDIT', 'MANAGE']) && $subject instanceof User;
    }

    /**
     * Voting mechanism.
     *
     * @param string         $attribute Voter attribute
     * @param mixed          $subject   Voter subject
     * @param TokenInterface $token     Authentication token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            'VIEW', 'EDIT' => $this->isAdminOrOwner($subject, $user),
            'MANAGE' => $this->isAdminUser($subject, $user),
            default => false,
        };
    }

    /**
     * Returns true if user is admin.
     *
     * @param mixed         $subject Voter subject
     * @param UserInterface $user    User
     *
     * @return bool
     */
    private function isAdminOrOwner(mixed $subject, UserInterface $user): bool
    {
        /**
         * @var User $user
         * @var User $subject
         */

        return $subject->getId() === $user->getId() || $user->isAdmin();
    }

    /**
     * Returns true if user is admin.
     *
     * @param mixed         $subject Voter subject
     * @param UserInterface $user    User
     *
     * @return bool
     */
    private function isAdminUser(mixed $subject, UserInterface $user): bool
    {
        /** @var User $user */

        return $user->isAdmin();
    }
}
