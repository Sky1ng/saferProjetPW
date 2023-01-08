<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'app_error')]
    public function index(): Response
    {
        //Si l'url n'est pas bonne, on renvoie sur la page d'erreur avec la page en dessous
        return $this->render('error/index.html.twig', [
            'controller_name' => 'ErrorController',
        ]);
    }

    public function notFound()
    {
        return new Response(
            '<!DOCTYPE html>
<html lang="en">


    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Bootstrap 5 404 Error Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>


    <body>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold">404</h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span> Page non trouvée.</p>
                <p class="lead">
                    La page que vous rechercher n\'est pas disponible.
                  </p>
                <a href="http://127.0.0.1:8000" class="btn btn-success">Retour à l\'accueil</a>
            </div>
        </div>
    </body>


</html>',
            Response::HTTP_NOT_FOUND
        );
    }
}
