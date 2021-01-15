<?php

namespace App\Controller;


use App\Repository\BuyerRepository;
use App\Entity\Filter;
use App\Form\FilterType;

use App\Repository\DepartmentRepository;
use App\Repository\FarmerRepository;
use App\Repository\TransactionRepository;
use App\Service\AveragePrice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(
        FarmerRepository $farmerRepository,
        Request $request,
        BuyerRepository $buyerRepository,
        TransactionRepository $transactionRepository,
        DepartmentRepository $departmentRepository,
        AveragePrice $averagePrice
    ): Response {
        $display = '';
        $department = '';
        $arrayOfCities = [];
        $farmersCities = $farmerRepository->findFarmersByCity();
        $farmers = $farmerRepository->findAll();
        foreach ($farmers as $farmer) {
            $averagePrices[$farmer->getId()] = $averagePrice->calculateByFarmer($farmer->getId());
            $products[$farmer->getId()] = array_column($transactionRepository->findProductsForFarmer($farmer->getId()), 'image');
        }

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
            if (isset($department)) {
                $departmentCities = $department->getCities();
                foreach($departmentCities as $departmentCity) {
                    $arrayOfCities[] = $departmentCity->getName();
                }
                foreach ($farmersCities as $farmer) {
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
            }
        }

        return $this->render('map.html.twig', [
            'form' => $form->createView(),
            'farmersCities' => $farmersResult ?? $farmersCities,
            'buyers' => $buyersResult ?? $buyers,
            'display' => $display,
            'department' => $department,
            'farmers' => $farmers,
            'averagePrices' => $averagePrices ?? [],
            'products' => $products ?? []
        ]);
    }
}
