<?php
/**
 * File service.
 */

namespace App\Service;

use App\Entity\File;
use App\Entity\Article;
use App\Repository\FileRepository;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\FileUploadServiceInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class File service.
 */
class FileService implements FileServiceInterface
{
    /**
     * File system service.
     */
    private Filesystem $filesystem;

    /**
     * File repository.
     */
    private FileRepository $fileRepository;

    /**
     * File upload service.
     */
    private FileUploadServiceInterface $fileUploadService;

    /**
     * Target directory.
     *
     * @var string
     */
    private string $targetDirectory;

    /**
     * Constructor.
     *
     * @param string                     $targetDirectory   Target directory
     * @param FileRepository             $fileRepository    File repository
     * @param FileUploadServiceInterface $fileUploadService File upload service
     * @param Filesystem                 $filesystem        Filesystem component
     */
    public function __construct(string $targetDirectory, FileRepository $fileRepository, FileUploadServiceInterface $fileUploadService, Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->fileRepository = $fileRepository;
        $this->targetDirectory = $targetDirectory;
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

    /**
     * Update file for article.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param File         $file         File entity
     * @param Article      $article      Article entity
     *
     * @return void
     */
    public function updateForArticle(UploadedFile $uploadedFile, File $file, Article $article): void
    {
        $filePath = $file->getPath();

        if (null !== $filePath) {
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filePath
            );

            $this->createForArticle($uploadedFile, $file, $article);
        }
    }
}
