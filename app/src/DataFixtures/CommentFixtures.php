<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class CommentFixtures.
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
     * Construct new comment fixtures object.
     *
     * @param ArticleRepository $articleRepository Article repository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
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

        foreach ($articles as $article) {
            for ($i = 0; $i < 15; ++$i) {
                $comment = new Comment();
                $comment->setHeader($this->faker->unique()->sentence);
                $comment->setContent($this->faker->realText);
                $comment->setArticle($article);

                $this->manager->persist($comment);
            }
        }

        $this->manager->flush();
    }
}
