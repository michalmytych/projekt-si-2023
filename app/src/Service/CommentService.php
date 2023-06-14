<?php
/**
 * Comment service.
 */

namespace App\Service;

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
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
}
