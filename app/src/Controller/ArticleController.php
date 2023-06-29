<?php
/**
 * Article controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\Type\ArticleType;
use App\Service\ArticleService;
use App\Service\CommentService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ArticleController.
 */
#[Route('/article')]
class ArticleController extends AbstractController
{
    /**
     * Article service.
     */
    private ArticleService $articleService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Comment service.
     */
    private CommentService $commentService;

    /**
     * Construct new article controller object.
     *
     * @param ArticleService      $articleService Article service
     * @param TranslatorInterface $translator     Translator
     * @param CommentService      $commentService Comment service
     */
    public function __construct(ArticleService $articleService, TranslatorInterface $translator, CommentService $commentService)
    {
        $this->articleService = $articleService;
        $this->commentService = $commentService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request Current HTTP request
     *
     * @return Response HTTP response
     *
     * @throws NonUniqueResultException
     */
    #[Route(
        name: 'article_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $filters = $this->getFilters($request);
        $page = $request->query->getInt('page', 1);
        $pagination = $this->articleService->getPaginatedList($page, $filters, $user);

        return $this->render(
            'article/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Article $article Article entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'article_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Article $article): Response
    {
        $user = $this->getUser();

        /** @var User $user */
        if (!$article->isPublished() && $user && !$user->isAdmin()) {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.no_permission')
            );

            return $this->redirectToRoute('article_index');
        }

        $comments = $this->commentService->getLatestByArticle($article);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
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
        name: 'article_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $user = $this->getUser();

        /** @var User $user */
        if (!$user || !$user->isAdmin()) {
            // @todo - is there a better way ?
            $this->addFlash(
                'danger',
                $this->translator->trans('message.no_permission')
            );

            return $this->redirectToRoute('article_index');
        }

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'article/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Article $article Article entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit',
        name: 'article_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('EDIT', subject: 'article')]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(
            ArticleType::class,
            $article,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('article_edit', ['id' => $article->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'article/edit.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Article $article Article entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'article_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'article')]
    public function delete(Request $request, Article $article): Response
    {
        $form = $this->createForm(FormType::class, $article, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('article_delete', ['id' => $article->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->delete($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'article/delete.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int, tag_id: int, status_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        return $filters;
    }
}
