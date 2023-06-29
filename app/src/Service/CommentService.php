<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\CommentRepository;

/**
 * Class CommentService.
 */
class CommentService
{
    private CommentRepository $commentRepository;

    /**
     * Construct new comment service object.
     *
     * @param CommentRepository $commentRepository Comment repository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Get latest comments by article.
     *
     * @param Article $article Article entity
     * @param int     $limit   Records limit
     *
     * @return array
     */
    public function getLatestByArticle(Article $article, int $limit = 5): array
    {
        return $this
            ->commentRepository
            ->findBy(
                ['article' => $article],
                ['id' => 'DESC'],
                $limit
            );
    }
}
