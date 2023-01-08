<?php

namespace App\Controller;

use App\Entity\Bien;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarteController extends AbstractController
{

    #[Route('/carte', name: 'app_carte')]
    public function index(EntityManagerInterface $em): Response
    {

        $locations = [];

        //On récupère tous les biens
        $code = $em->getRepository(Bien::class)->findAll();
        $codesPostaux = [];
        //On récupère tous les codes postaux
        for ($i = 0; $i < count($code); $i++) {
            $codesPostaux[] = $code[$i]->getLocalisation();
        }
        //Pour chaque code postal on récupère les coordonnées et on les stocks dans locations
        foreach ($codesPostaux as $codePostal) {
            $location = $this->getCoordinates($codePostal);
            $latitude = $location['lat'];
            $longitude = $location['lon'];

            $locations[] = [
                'lat' => $latitude,
                'lon' => $longitude,
            ];
        }

        return $this->render('carte/carte.html.twig', [
            'locations' => $locations
        ]);
    }
    public function getCoordinates(string $address)
    {

        //UTILISATION DE OPENCAGEDATA

        $apiKey = 'API_KEY';
        $endpoint = 'https://api.opencagedata.com/geocode/v1/json';

        $client = HttpClient::create();
        $response = $client->request('GET', $endpoint, [
            'query' => [
                'q' => $address,
                'key' => $apiKey
            ]
        ]);

        $data = $response->toArray();
        $latitude = $data['results'][0]['geometry']['lat'];
        $longitude = $data['results'][0]['geometry']['lng'];


        return [
            'lat' => $latitude,
            'lon' => $longitude,

        ];
    }
}
