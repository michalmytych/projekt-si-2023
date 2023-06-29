<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class CommentFixtures.
 *
 * @class CommentFixtures
 */
class CommentFixtures extends AbstractBaseFixtures implements OrderedFixtureInterface
{
    /**
     * Article repository.
     *
     * @var ArticleRepository $articleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * User repository.
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * Construct new comment fixtures object.
     *
     * @param ArticleRepository $articleRepository Article repository
     * @param UserRepository    $userRepository    User repository
     */
    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get order of fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 3;
    }

    /**
     * Load fixture data data.
     *
     * @return void
     */
    public function loadData(): void
    {
        $articles = $this->articleRepository->findAll();
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            foreach ($articles as $article) {
                for ($i = 0; $i < 3; ++$i) {
                    $comment = new Comment();
                    $comment->setHeader($this->faker->unique()->sentence);
                    $comment->setContent($this->faker->realText);
                    $comment->setAuthor($user);
                    $comment->setArticle($article);

                    $this->manager->persist($comment);
                }
            }
        }

        $this->manager->flush();
    }
}
