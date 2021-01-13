<?php

namespace App\Controller;

use App\Repository\FarmerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(FarmerRepository $farmerRepository): Response
    {
        $farmers = $farmerRepository->findAll();

        return $this->render('map.html.twig', ['farmers' => $farmers]);
    }
}
