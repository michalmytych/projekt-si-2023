<?php
/**
 * Article security voter.
 */

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class ArticleVoter.
 */
class ArticleVoter extends Voter
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
        return in_array($attribute, ['VIEW', 'EDIT', 'DELETE']) && $subject instanceof Article;
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
            'VIEW' => $this->isAdminOrSubjectIsPublished($subject, $user),
            'EDIT', 'DELETE' => $this->isAdminUser($subject, $user),
            default => false,
        };
    }

    /**
     * Returns true if user has ROLE_ADMIN or subject (Article) has status STATUS_PUBLISHED.
     *
     * @param mixed         $subject Subject
     * @param UserInterface $user    User
     *
     * @return bool Is admin or subject is published
     */
    private function isAdminOrSubjectIsPublished(mixed $subject, UserInterface $user): bool
    {
        /*
         * @var Article $subject
         * @var User $user
         */

        return $subject->isPublished() || $user->isAdmin();
    }

    /**
     * Returns true if user is admin.
     *
     * @param mixed         $subject Subject
     * @param UserInterface $user    User
     *
     * @return bool Is admin
     */
    private function isAdminUser(mixed $subject, UserInterface $user): bool
    {
        /* @var User $user */

        return $user->isAdmin();
    }
}
