<?php
/**
 * Category security voter.
 */

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Category;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class CategoryVoter.
 */
class CategoryVoter extends Voter
{
    /**
     * Permissions.
     *
     * @param string $attribute Attribute
     * @param mixed  $subject   Subject
     *
     * @return bool If supports
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT', 'DELETE']) && $subject instanceof Category;
    }

    /**
     * Voting mechanism.
     *
     * @param string         $attribute Attribute
     * @param mixed          $subject   Subject
     * @param TokenInterface $token     Token
     *
     * @return bool Vote on attribute
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            'EDIT', 'DELETE' => $this->isAdminUser($subject, $user),
            default => false,
        };
    }

    /**
     * Returns true if user is admin.
     *
     * @param mixed         $subject Subject
     * @param UserInterface $user    User
     *
     * @return bool If is admin user
     */
    private function isAdminUser(mixed $subject, UserInterface $user): bool
    {
        /* @var User $user */

        return $user->isAdmin();
    }
}
