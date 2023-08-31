<?php
/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use App\Form\Type\EditProfileType;
use App\Form\Type\RegistrationType;
use App\Form\Type\ChangePasswordType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * Translator interface.
     *
     * @var TranslatorInterface Translator interface
     */
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Register new user.
     *
     * @param Request                     $request           HTTP Request
     * @param UserService                 $service           User service
     * @param UserPasswordHasherInterface $passwordHasher    User password hasher interface
     * @param UserAuthenticatorInterface  $userAuthenticator User authenticator
     * @param LoginFormAuthenticator      $authenticator     Login form authenticator handler
     *
     * @return Response Response
     */
    #[Route(
        '/register',
        name: 'app_register',
        methods: 'GET|POST'
    )]
    public function register(Request $request, UserService $service, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('article_index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );
            $user->setRoles([UserRole::ROLE_USER->value]);

            $service->save($user);
            $this->addFlash('success', 'registration_complete');

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Change password.
     *
     * @param Request                     $request        HTTP Request
     * @param User                        $user           User
     * @param UserRepository              $userRepository User repository
     * @param UserPasswordHasherInterface $passwordHasher User password encoder interface
     *
     * @return Response Response
     */
    #[Route(
        '/user/{id}/change-password',
        name: 'user_change_password',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('EDIT', subject: 'user')]
    public function changePassword(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $userRepository->save($user);
            $this->addFlash('success', 'message_password_changed');
            $this->redirectToRoute('article_index');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Change credentials.
     *
     * @param Request        $request        HTTP Request
     * @param User           $user           User
     * @param UserRepository $userRepository User repository
     *
     * @return Response Response
     */
    #[Route(
        '/user/{id}/edit',
        name: 'user_edit_profile',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('EDIT', subject: 'user')]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EditProfileType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newEmail = $form->get('email')->getData();
            $newNickname = $form->get('nickname')->getData();

            $user->setEmail($newEmail);
            $user->setNickname($newNickname);

            $userRepository->save($user);
            $this->addFlash('success', $this->translator->trans('message_profile_updated_successfully'));
            $this->redirectToRoute('article_index');
        }

        return $this->render('security/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
