<?php
/**
 * File service.
 */

namespace App\Service;

use App\Entity\Article;
use App\Entity\File;
use App\Repository\FileRepository;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\FileUploadServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class File service.
 */
class FileService implements FileServiceInterface
{
    /**
     * File repository.
     */
    private FileRepository $fileRepository;

    /**
     * File upload service.
     */
    private FileUploadServiceInterface $fileUploadService;

    /**
     * Constructor.
     *
     * @param FileRepository             $fileRepository    File repository
     * @param FileUploadServiceInterface $fileUploadService File upload service
     */
    public function __construct(FileRepository $fileRepository, FileUploadServiceInterface $fileUploadService)
    {
        $this->fileRepository = $fileRepository;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Create image file for article.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param File         $file         File entity
     * @param Article      $article      Article entity
     */
    public function createForArticle(UploadedFile $uploadedFile, File $file, Article $article): void
    {
        $generatedFileName = $this->fileUploadService->upload($uploadedFile);

        $file->addArticle($article);
        $file->setPath($generatedFileName);
        $this->fileRepository->save($file);
    }
}