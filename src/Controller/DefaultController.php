<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/{id<\d+>?}/{hash<\w+>?}", name = "main")
     * @return Response
     */
    public function index(?int $id, ?string $hash)
    {
        return $this->render('default/index.html.twig');
    }
}