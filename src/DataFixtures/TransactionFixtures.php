<?php


namespace App\DataFixtures;


use App\Entity\Products;
use App\Entity\Transaction;
use App\Repository\BuyerRepository;
use App\Repository\FarmerRepository;
use App\Repository\ProductsRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TransactionFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    const LIMIT = 5000;

    /**
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container;
    /**
     * @var BuyerRepository
     */
    private BuyerRepository $buyerRepository;

    private FarmerRepository $farmerRepository;
    /**
     * @var ProductsRepository
     */
    private ProductsRepository $productsRepository;

    public function __construct(BuyerRepository $buyerRepository,
                                FarmerRepository $farmerRepository,
                                ProductsRepository $productsRepository)
    {
        $this->buyerRepository = $buyerRepository;
        $this->farmerRepository = $farmerRepository;
        $this->productsRepository = $productsRepository;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath ("./") . "/src/DataFixtures/transactions.csv";

        $data = $serializer->decode(file_get_contents($filepath), 'csv');

        for ($i=0; $i < count($data) && $i < self::LIMIT; $i++) {
            $line = $data[$i];
            $transaction = new Transaction();
            $transaction->setQuantity($line['quantity']);
            $transaction->setPrice($line['price']);
            $transaction->setBuyer($this->getReference('buyer_'.rand(0,7)));
            $transaction->setFarmer($this->getReference('farmer_' . rand(1, 500)));
            $transaction->setProduct($this->getReference('product_' . rand(0, 22)));
            $date = new \DateTime($line['created_at']);
            $transaction->setCreatedAt($date);
            $manager->persist($transaction);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [BuyerFixtures::class];
    }
}