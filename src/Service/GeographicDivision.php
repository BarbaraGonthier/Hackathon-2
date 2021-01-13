<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeographicDivision
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDepartments(): array
    {
        $response = $this->client->request(
            'GET',
            'https://geo.api.gouv.fr/departements'
        );
        return $response->toArray();
    }

    public function getDepartment(string $inseeCode): string
    {
        $departments = $this->getDepartments();
        $numberDepartment = substr($inseeCode, 0, 2);
        $key = array_search($numberDepartment, array_column($departments, 'code'));
        return $departments[$key]['nom'];
    }
}