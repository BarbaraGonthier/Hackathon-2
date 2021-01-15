<?php

namespace App\Service;

use App\Repository\CityRepository;
use App\Repository\DepartmentRepository;
use App\Repository\FarmerRepository;
use App\Repository\TransactionRepository;

class AveragePrice
{
    private CityRepository $cityRepository;

    private FarmerRepository $farmerRepository;

    private TransactionRepository $transactionRepository;

    private DepartmentRepository $departmentRepository;

    public function __construct(
        CityRepository $cityRepository,
        FarmerRepository $farmerRepository,
        TransactionRepository $transactionRepository,
        DepartmentRepository $departmentRepository
    ){
        $this->cityRepository = $cityRepository;
        $this->farmerRepository = $farmerRepository;
        $this->transactionRepository = $transactionRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function calculateByFarmer(int $idFarmer): ?float
    {
        $transactions = $this->transactionRepository->findBy(['farmer' => $idFarmer]);
        $sumPrices = 0;
        foreach ($transactions as $transaction) {
            $sumPrices += $transaction->getPrice();
        }
        return count($transactions) > 0 ? round($sumPrices / count($transactions), 2) : 0;
    }

    public function calculateForDepartment(int $idCity): ?float
    {
        $city = $this->cityRepository->findOneBy(['id' => $idCity]);
        $department = $city->getDepartment();
        $cities = $this->cityRepository->findBy(['department' => $department]);
        $farmers = [];
        $transactions = [];
        foreach ($cities as $city) {
            $farmersCity = $this->farmerRepository->findBy(['city' => $city->getId()]);
            if ($farmersCity) {
                $farmers[] = $farmersCity;
            }
        }
        for ($i = 0; $i < count($farmers); $i++) {
            $transactionsFarmer = $this->transactionRepository->findBy(['farmer' => $farmers[$i][0]->getId()]);
            if ($transactionsFarmer) {
                $transactions[] = $transactionsFarmer;
            }
        }
        $sumPrices = 0;
        for ($i = 0; $i < count($transactions); $i++) {
            $sumPrices += $transactions[$i][0]->getPrice();
        }
        return count($transactions) > 0 ? round($sumPrices / count($transactions), 2) : 0;
    }
}