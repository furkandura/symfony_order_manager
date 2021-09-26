<?php

namespace App\Repository;

use DateTime;
use App\Entity\Order;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Order::class);
        $this->manager = $manager;
    }

    /**
     * Sipariş oluşturan func.
     *
     * @param integer $userId
     * @param integer $productId
     * @param integer $quantity
     * @param string $address
     * @param string|null $shippingDate
     * @return void
     */
    public function createOrder(int $userId, int $productId, int $quantity, string $address, string $shippingDate = null) :void
    {
        $order = new Order();
        $order->setUserId($userId);
        $order->setProductId($productId);
        $order->setAddress($address);
        $order->setQuantity($quantity);
        $order->setCreatedAt(new DateTime("now"));
        
        if($shippingDate != null) {
            $order->setShippingDate($shippingDate);
        }

        $this->manager->persist($order);
        $this->manager->flush();
    }

    /**
     * Sipariş güncelleyen func.
     *
     * @param integer $userId
     * @param integer $orderId
     * @param integer $productId
     * @param integer $quantity
     * @param string $address
     * @param string|null $shippingDate
     * @return void
     */
    public function updateOrder(int $userId, int $orderId, int $productId, int $quantity, string $address, $shippingDate = null) :void
    {
        $order = $this->findOneBy([
            "id" => $orderId,
            "userId" => $userId
        ]);

        $order->setProductId($productId);
        $order->setQuantity($quantity);
        $order->setAddress($address);
        $order->setShippingDate($shippingDate);
        $order->setUpdatedAt(new DateTime("now"));

        $this->manager->persist($order);
        $this->manager->flush();
    }

    /**
     * Sipariş teslimat aşamasına geçmiş mi kontrolünü yapan func.
     *
     * @param integer $orderId
     * @return boolean
     */
    public function checkShippingDate(int $orderId) : bool 
    {
        $data = $this->findOneBy([ "id" => $orderId ]);

        if($data->getShippingDate() == null) {
            return true;
        } 

        return false;
    }

    /**
     * Sipariş giriş yapmış kullanıcının mı kontrolünü yapan func.
     *
     * @param integer $orderId
     * @param integer $userId
     * @return boolean
     */
    public function chechIsAuthOrder(int $orderId, int $userId): bool
    {
        $data = $this->findOneBy([
            "id" => $orderId,
            "userId" => $userId
        ]);

        if($data == null) {
            return false;
        }

        return true;
    }


    /**
     * Tekil sipariş array olarak getiren func.
     *
     * @param integer $orderId
     */
    public function getAuthSingleOrder(int $orderId)
    {
        return $this->createQueryBuilder("o")
            ->andWhere("o.id = :id")
            ->setParameter("id", $orderId)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Tüm siparişlerini array olarak getiren func.
     *
     * @param integer $userId
     */
    public function getAuthAllOrders(int $userId)
    {
        return $this->createQueryBuilder("o")
            ->andWhere("o.userId = :user_id")
            ->setParameter("user_id", $userId)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }


}
