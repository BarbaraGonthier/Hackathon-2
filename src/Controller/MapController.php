<?php

namespace App\Controller;

use App\Repository\BuyerRepository;
use App\Repository\FarmerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(FarmerRepository $farmerRepository, BuyerRepository $buyerRepository): Response
    {
        $farmers = $farmerRepository->findFarmersByCity();
        $buyers = $buyerRepository->findBuyersByCity();

        return $this->render('map.html.twig', ['farmers' => $farmers, 'buyers' => $buyers]);
    }
}
