<?php
/**
 * Comment security voter.
 */

namespace App\Security\Voter;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class CommentVoter.
 */
class CommentVoter extends Voter
{
    /**
     * Permissions.
     *
     * @param string $attribute Attribute
     * @param        $subject
     *
     * @return bool If supports
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['DELETE']) && $subject instanceof Comment;
    }

    /**
     * Voting mechanism.
     * @param string         $attribute Attribute
     * @param                $subject
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
            'DELETE' => $this->isAdminUser($subject, $user),
            default => false,
        };
    }

    /**
     * Returns true if user is admin.
     * @param mixed         $subject Subject
     * @param UserInterface $user    User
     *
     * @return bool Is admin user
     */
    private function isAdminUser(mixed $subject, UserInterface $user): bool
    {
        /* @var User $user */

        return $user->isAdmin();
    }
}
