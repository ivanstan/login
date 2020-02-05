<?php

namespace App\Repository;

use App\Entity\User;
use App\Model\Collection\Parameters;
use App\Model\Collection\EntityCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function collection(Parameters $params): EntityCollection {
        $builder = $this->createQueryBuilder('u');

        // search
        if ($params->hasSearch()) {
            $builder
                ->where(
                    $builder->expr()->orX(
                        $builder->expr()->like('u.email', ':search')
                    )
                )
                ->setParameter('search', '%' . $params->getSearch() . '%');
        }

        // get total
        $total = \count($builder->getQuery()->getResult());

        // sort
//        $builder->orderBy('u.' . $sort, $sortDir);

        // limit
        $builder->setMaxResults($params->getPageSize());
        $builder->setFirstResult($params->getOffset());

        $collection = new EntityCollection();

        $collection->setCollection($builder->getQuery()->getResult());
        $collection->setTotal($total);

        return $collection;
    }
}
