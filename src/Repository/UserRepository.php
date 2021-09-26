<?php

namespace App\Repository;

use DateTime;
use App\Entity\User;
use App\Helper\HashHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository 
{
    public $manager;
    
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
    }

    /**
     * Müşteri oluşturan func.
     *
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $surname
     * @param string $phone
     * @param string $email
     * @return void
     */
    public function createUser(string $username, string $password, string $fullName, ?string $phone) :void
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword(HashHelper::hash($password));
        $user->setFullName($fullName);
        $user->setPhone($phone);
        $user->setCreatedAt(new DateTime("now"));


        $this->manager->persist($user);
        $this->manager->flush();
    }

    /**
     * Username ile müşteri bulan func.
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username) : ?User
    {
        return $this->createQueryBuilder("users")
                    ->andWhere("users.username = :val")
                    ->setParameter("val", $username)
                    ->getQuery()
                    ->getOneOrNullResult();
    }


    
}
