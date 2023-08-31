<?php
/**
 * Comment repository.
 */

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * Construct new comment repository object.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Save record in a database.
     *
     * @param Comment $comment Comment
     */
    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment
     */
    public function delete(Comment $comment): void
    {
        $this->_em->remove($comment);
        $this->_em->flush();
    }

    /**
     * Remove tag entity.
     *
     * @param Comment $comment Comment
     * @param bool    $flush   Flush
     */
    public function remove(Comment $comment, bool $flush = false): void
    {
        $this->getEntityManager()->remove($comment);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
