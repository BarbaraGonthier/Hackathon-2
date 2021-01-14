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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
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
            $buyer->setLogo($line['logo']);
            $buyer->setCity($this->getReference('city_'.rand(10,4800)));
            $manager->persist($buyer);
            $this->addReference('buyer_' .$i,$buyer);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CityFixtures::class];
    }
}