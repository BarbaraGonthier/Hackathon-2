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
        $department = '';
        $arrayOfCities = [];
        $farmers = $farmerRepository->findFarmersByCity();
        $buyers = $buyerRepository->findBuyersByCity();
      
        $filter = new Filter();
        $form = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            if ($filters->getRole() == 'farmers') {
                $display = 'farmers';
            } elseif ($filters->getRole() == 'buyers'){
                $display = 'buyers';
            }
            $department = $filters->getDepartment();
            $departmentCities = $department->getCities();
            foreach($departmentCities as $departmentCity) {
                $arrayOfCities[] = $departmentCity->getName();
            }
            foreach ($farmers as $farmer) {
                $farmerCity = $farmer['name'];
                if (in_array($farmerCity, $arrayOfCities)) {
                    $farmersResult[] = $farmer;
                }
            }
            foreach ($buyers as $buyer) {
                $buyerCity = $buyer['name'];
                if (in_array($buyerCity, $arrayOfCities)) {
                    $buyersResult[] = $buyer;
                }
            }

            $farmers = [];
//            $buyers = [];
        }

        return $this->render('map.html.twig', [
            'form' => $form->createView(),
            'farmers' => $farmersResult ?? $farmers,
            'buyers' => $buyersResult ?? $buyers,
            'display' => $display,
            'department' => $department
        ]);
    }
}
