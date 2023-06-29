<?php
/**
 * Tag security voter.
 */

namespace App\Security\Voter;

use App\Entity\Tag;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class TagVoter.
 */
class TagVoter extends Voter
{
    /**
     * Permissions.
     *
     * @param string $attribute Voter attribute
     * @param mixed  $subject   Voter subject
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT', 'DELETE']) && $subject instanceof Tag;
    }

    /**
     * Voting mechanism.
     *
     * @param string         $attribute Voter attribute
     * @param mixed          $subject   Voter subject
     * @param TokenInterface $token     Authentication token
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
     * @param mixed         $subject Voter subject
     * @param UserInterface $user    User
     */
    private function isAdminUser(mixed $subject, UserInterface $user): bool
    {
        /* @var User $user */

        return $user->isAdmin();
    }
}
