<?php
/**
 * File service interface.
 */

namespace App\Service\Interface;

use App\Entity\Article;
use App\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class File service.
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
}