<?php
/**
 * File service interface.
 */

namespace App\Service\Interface;

use App\Entity\File;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface FileServiceInterface.
 */
interface FileServiceInterface
{
    /**
     * Create image file for article.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param File         $file         File entity
     * @param Article      $article      Article entity
     */
    public function createForArticle(UploadedFile $uploadedFile, File $file, Article $article): void;

    /**
     * Update file for article.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param File         $file         File
     * @param Article      $article      Article
     */
    public function updateForArticle(UploadedFile $uploadedFile, File $file, Article $article): void;
}
