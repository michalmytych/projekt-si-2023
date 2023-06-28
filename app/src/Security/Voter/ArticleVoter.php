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
     * @param string $attribute Voter attribute
     * @param mixed  $subject   Voter subject
     *
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['VIEW', 'CREATE', 'EDIT', 'DELETE']) && $subject instanceof Article;
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
            'VIEW' => $this->isAdminOrSubjectIsPublished($subject, $user),
            'CREATE', 'EDIT', 'DELETE' => $this->isAdminUser($user),
            default => false,
        };
    }

    /**
     * Returns true if user has ROLE_ADMIN or subject (Article) has status STATUS_PUBLISHED.
     *
     * @param mixed         $subject Voter subject
     * @param UserInterface $user    User
     *
     * @return bool
     */
    private function isAdminOrSubjectIsPublished(mixed $subject, UserInterface $user): bool
    {
        /**
         * @var Article $subject
         * @var User $user
         */

        return $subject->isPublished() || $user->isAdmin();
    }

    /**
     * Returns true if user is admin.
     *
     * @param UserInterface $user User
     *
     * @return bool
     */
    private function isAdminUser(UserInterface $user): bool
    {
        /** @var User $user */

        return $user->isAdmin();
    }
}
