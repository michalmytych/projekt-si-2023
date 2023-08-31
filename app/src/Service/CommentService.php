<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Service\Interface\CommentServiceInterface;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
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
     * @param Comment $comment Comment
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Get latest comments by article.
     *
     * @param Article $article Article
     * @param int     $limit   Limit
     *
     * @return array Latest comments by article
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
