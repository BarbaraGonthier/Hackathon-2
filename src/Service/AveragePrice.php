<?php

namespace App\Service;

use App\Repository\CityRepository;
use App\Repository\FarmerRepository;
use App\Repository\TransactionRepository;

class AveragePrice
{
    private CityRepository $cityRepository;

    private FarmerRepository $farmerRepository;

    private TransactionRepository $transactionRepository;

    public function __construct(
        CityRepository $cityRepository,
        FarmerRepository $farmerRepository,
        TransactionRepository $transactionRepository
    ){
        $this->cityRepository = $cityRepository;
        $this->farmerRepository = $farmerRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function calculateByDepartment(int $idDepartment): ?float
    {
        $cities = $this->cityRepository->findBy(['department_id' => $idDepartment]);
        $farmers = [];
        $transactions = [];
        foreach ($cities as $city) {
            $farmersCity = $this->farmerRepository->findBy(['city_id' => $city->getId()]);
            $farmers[] = $farmersCity;
        }
        foreach ($farmers as $farmer) {
            $transactionsFarmer = $this->transactionRepository->findBy(['farmer_id' => $farmer->getId()]);
            $transactions[] = $transactionsFarmer;
        }
        $sumPrices = 0;
        foreach ($transactions as $transaction) {
            $sumPrices += $transaction->getPrice() * $transaction->getQuantity();
        }
        return $sumPrices / count($transactions);
    }
}