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

        $code = $em->getRepository(Bien::class)->findAll();
        $codesPostaux = [];
        for ($i = 0; $i < count($code); $i++) {
            $codesPostaux[] = $code[$i]->getLocalisation();
        }
        foreach ($codesPostaux as $codePostal) {
            $location = $this->getCoordinates($codePostal);
            $latitude = $location['lat'];
            $longitude = $location['lon'];

            $locations[] = [
                'lat' => $latitude,
                'lon' => $longitude,
            ];
            // Get lat and long by addres
        }

        return $this->render('carte/carte.html.twig', [
            'locations' => $locations
        ]);
    }
    public function getCoordinates(string $address)
    {
        $apiKey = '02de8520f3e341f7b9bdc11b3279f6d2';
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
