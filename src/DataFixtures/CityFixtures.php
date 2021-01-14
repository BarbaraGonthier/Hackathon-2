<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Service\GeographicDivision;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CityFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    const LIMIT = 5000;

    /**
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container;

    private GeographicDivision $geographicDivision;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct(GeographicDivision $geographicDivision)
    {
        $this->geographicDivision = $geographicDivision;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath ("./") . "/src/DataFixtures/cities.csv";

        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data) && $i < self::LIMIT; $i++) {
            $line = $data[$i];
            $city = new City();
            $city->setName($line['city']);
            $city->setZipcode($line['zipcode']);
            $city->setInseeCode($line['insee_code']);
            $city->setLatitude($line['lat']);
            $city->setLongitude($line['long']);
            $city->setDepartment($this->geographicDivision->getDepartment($line['insee_code']));
            $this->addReference('city_' . $i, $city);
            $manager->persist($city);

        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [DepartmentFixtures::class];
    }
}