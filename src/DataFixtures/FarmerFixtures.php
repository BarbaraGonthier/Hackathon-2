<?php


namespace App\DataFixtures;

use App\Entity\Farmer;
use App\Repository\CityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FarmerFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    const LIMIT = 500;

    /**
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container;
    /**
     * @var CityRepository
     */
    private CityRepository $cityRepository;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath ("./") . "/src/DataFixtures/farmers.csv";
        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data) && $i < self::LIMIT; $i++) {
            $line = $data[$i];
            $farmer = new Farmer();
            $farmer->setCity($this->getReference('city_'.rand(10,4800)));
            $date = new \DateTime($line['registered_at']);
            $farmer->setRegisteredAt($date);
            $farmer->setFirstName($line['first_name']);
            $farmer->setLastName($line['last_name']);
            $farmer->setFarmSize($line['farm_size']);
            $manager->persist($farmer);
            $this->addReference('farmer_' .$i,$farmer);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [CityFixtures::class];
    }
}