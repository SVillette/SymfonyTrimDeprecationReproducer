<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function indexAction(Request $request): Response
    {
        return $this->render('index.html.twig');
    }

}
