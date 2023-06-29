<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Entity\Enum\UserRole;
use App\Form\Type\UserRoleType;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * User service.
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * Translator.
     *
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;


    /**
     * Construct new user controller object.
     *
     * @param UserService         $userService
     * @param TranslatorInterface $translator
     */
    public function __construct(UserService $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'user_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        /** @var User $user */
        if (!$user || !$user->isAdmin()) {
            // @todo - is there a better way ?
            $this->addFlash(
                'danger',
                $this->translator->trans('message.no_permission')
            );

            return $this->redirectToRoute('category_index');
        }

        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->getPaginatedList($page);

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param User $user
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'user_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    #[IsGranted('VIEW', subject: 'user')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     *
     * @throws NonUniqueResultException
     */
    #[Route(
        '/{id}/edit-role',
        name: 'user_edit_role',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('MANAGE', subject: 'user')]
    public function editRole(Request $request, User $user): Response
    {
        $form = $this->createForm(
            UserRoleType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('user_edit_role', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRoles = $form->get('roles')->getData();

            if (!in_array(UserRole::ROLE_USER->value, $newRoles)) { // @todo to service
                $this->addFlash(
                    'danger',
                    $this->translator->trans('message.cannot_delete_role_user')
                );

                return $this->redirectToRoute('user_edit_role', ['id' => $user->getId()]);
            }

            $latestAdminUser = $this->userService->getLatestAdminUser();
            if ($latestAdminUser && $latestAdminUser->getId() === $user->getId()) { // @todo to service
                $this->addFlash(
                    'danger',
                    $this->translator->trans('message.cannot_delete_admin_role_from_latest_admin_user')
                );

                return $this->redirectToRoute('user_edit_role', ['id' => $user->getId()]);
            }

            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/edit_role.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
