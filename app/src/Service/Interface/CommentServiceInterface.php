<?php
/**
 * Comment service interface.
 */

namespace App\Service\Interface;

use App\Entity\Article;
use App\Entity\Comment;

/**
 * Interface CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Save entity.
     *
     * @param Comment $comment Comment
     */
    public function save(Comment $comment): void;

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment
     */
    public function delete(Comment $comment): void;

    /**
     * Get latest comments by article.
     *
     * @param Article $article Article
     * @param int     $limit   Limit
     *
     * @return array
     */
    public function getLatestByArticle(Article $article, int $limit = 5): array;
}
