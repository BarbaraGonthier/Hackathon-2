<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Service\GeographicDivision;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DepartmentFixtures extends Fixture
{

    private GeographicDivision $geographicDivision;

    public function __construct(GeographicDivision $geographicDivision)
    {
        $this->geographicDivision = $geographicDivision;
    }

    public function load(ObjectManager $manager)
    {
        $departments = $this->geographicDivision->getDepartments();

        foreach ($departments as $department) {
            $dep = new Department();
            $dep->setName($department['nom']);
            $dep->setCode($department['code']);
            $manager->persist($dep);

        }
        $manager->flush();
    }
}