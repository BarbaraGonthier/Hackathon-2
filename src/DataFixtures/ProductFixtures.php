<?php

namespace App\DataFixtures;


use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProductFixtures extends Fixture implements ContainerAwareInterface
{
    const LIMIT = 23;

    /**
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath ("./") . "/src/DataFixtures/products.csv";

        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data) && $i < self::LIMIT; $i++) {
            $line = $data[$i];
            $product = new Products();
            $product->setName($line['name']);
            $product->setCategory($line['category']);
            $product->setImage($line['image']);
            $manager->persist($product);
            $this->addReference('product_' . $i,$product);
        }
        $manager->flush();
    }
}