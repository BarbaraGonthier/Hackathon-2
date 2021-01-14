<?php


namespace App\DataFixtures;

use App\Entity\Buyer;
use App\Repository\CityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BuyerFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    const LIMIT = 8;

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
     */
    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath ("./") . "/src/DataFixtures/buyers.csv";

        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data) && $i < self::LIMIT; $i++) {
            $line = $data[$i];
            $buyer = new Buyer();
            $buyer->setName($line['name']);
            $buyer->setType($line['type']);
            $buyer->setCity($this->cityRepository->findOneBy(['zipcode' => $line['city_id']]));
            $manager->persist($buyer);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CityFixtures::class];
    }
}