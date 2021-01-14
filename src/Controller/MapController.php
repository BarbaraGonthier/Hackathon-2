<?php

namespace App\Controller;


use App\Repository\BuyerRepository;
use App\Entity\Filter;
use App\Form\FilterType;

use App\Repository\FarmerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(FarmerRepository $farmerRepository, Request $request, BuyerRepository $buyerRepository): Response

    {
        $display = '';
        $farmers = $farmerRepository->findFarmersByCity();
        $buyers = $buyerRepository->findBuyersByCity();
      
        $filter = new Filter();
        $form = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            $filters->getRole() == 'farmers' ? $display = 'farmers' : $display = 'buyers';
        }

        return $this->render('map.html.twig', [
            'form' => $form->createView(),
            'farmers' => $farmers,
            'buyers' => $buyers,
            'display' => $display
        ]);
    }
}
