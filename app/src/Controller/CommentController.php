<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Form\Type\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Interface\ArticleServiceInterface;
use App\Service\Interface\CommentServiceInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Comment service.
     */
    private CommentServiceInterface $commentService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Article service.
     */
    protected ArticleServiceInterface $articleService;

    /**
     * Construct new comment controller object.
     *
     * @param CommentServiceInterface $commentService Comment service
     * @param ArticleServiceInterface $articleService Article service
     * @param TranslatorInterface     $translator     Translator
     */
    public function __construct(CommentServiceInterface $commentService, ArticleServiceInterface $articleService, TranslatorInterface $translator)
    {
        $this->commentService = $commentService;
        $this->articleService = $articleService;
        $this->translator = $translator;
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'comment')]
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(FormType::class, $comment, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'comment_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $commentedArticleId = $request->query->get('commented_article_id');
        $comment = new Comment();
        $article = $this->articleService->findOneById($commentedArticleId);
        $comment->setArticle($article);
        $comment->setAuthor($user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
        }

        if (null === $commentedArticleId) {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.bad_request')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'comment/create.html.twig',
            [
                'article' => $article,
                'form' => $form->createView(),
            ]
        );
    }
}
