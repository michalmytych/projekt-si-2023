<?php
/**
 * Home controller.
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController.
 */
#[Route('/')]
class HomeController extends AbstractController
{
    public const HOME_REDIRECT_ROUTE = 'article_index';

    /**
     * Index action.
     *
     * @return RedirectResponse HTTP response
     */
    #[Route(
        name: 'home_index',
        methods: 'GET'
    )]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute(self::HOME_REDIRECT_ROUTE);
    }
}
