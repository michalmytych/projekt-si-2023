<?php
/**
 * File controller.
 */

namespace App\Controller;

use App\Entity\File;
use App\Entity\Article;
use App\Form\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Interface\FileServiceInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class FileController.
 */
#[Route('/file')]
class FileController extends AbstractController
{
    /**
     * File service.
     */
    private FileServiceInterface $fileService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param FileServiceInterface $fileService File service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(FileServiceInterface $fileService, TranslatorInterface $translator)
    {
        $this->fileService = $fileService;
        $this->translator = $translator;
    }

    /**
     * Create action.
     *
     * @param Article      $article Article
     * @param RequestAlias $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create-for-article/{id}',
        name: 'file_create_for_article',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function createForArticle(Article $article, RequestAlias $request): Response
    {
        $file = new File();
        $form = $this->createForm(
            FileType::class,
            $file,
            ['action' => $this->generateUrl('file_create_for_article', ['id' => $article->getId()])]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('main_image_file')->getData();
            $this->fileService->createForArticle(
                $uploadedFile,
                $file,
                $article
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'file/create.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

}