<?php

namespace App\Service;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeographicDivision
{
    private $client;

    private $departmentRepository;

    public function __construct(HttpClientInterface $client, DepartmentRepository $departmentRepository)
    {
        $this->client = $client;
        $this->departmentRepository = $departmentRepository;
    }

    public function getDepartments(): array
    {
        $response = $this->client->request(
            'GET',
            'https://geo.api.gouv.fr/departements'
        );
        return $response->toArray();
    }

    public function getDepartment(string $inseeCode): ?Department
    {
        $numberDepartment = substr($inseeCode, 0, 2);
        return $this->departmentRepository->findOneBy(['code' => $numberDepartment]);
    }
}