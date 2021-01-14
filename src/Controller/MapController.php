<?php

namespace App\Controller;

use App\Repository\FarmerRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(FarmerRepository $farmerRepository, TransactionRepository $transactionRepository): Response
    {
        $farmersCity = $farmerRepository->findFarmersByCity();
        $farmers = $farmerRepository->findAll();
        $averagePrices = $transactionRepository->findAveragePrices();

        return $this->render('map.html.twig', [
            'farmersCity' => $farmersCity,
            'farmers' => $farmers,
            'averagePrices' => $averagePrices
        ]);
    }
}
