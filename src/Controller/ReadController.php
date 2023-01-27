<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadController extends AbstractController
{
    #[Route('/read', name: 'app_read')]
    public function index(): Response
    {
        return $this->render('read/index.html.twig', [
            'controller_name' => 'ReadController',
        ]);
    }
}
