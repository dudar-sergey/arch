<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\Rate;
use App\Repository\DTO\CNBReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @extends ServiceEntityRepository<Rate>
 *
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private SerializerInterface $serializer)
    {
        parent::__construct($registry, Rate::class);
    }

    public function save(Rate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Rate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /** @param Rate[] $rates */
    public function saveCollection(array $rates): void
    {
        foreach ($rates as $rate) {
            $entity = $this->findOneBy(['date' => $rate->getDate(), 'code' => $rate->getCode()]);

            if($entity) {
                continue;
            } else {
                $this->getEntityManager()->persist($rate);
            }
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @param array $currencies
     * @return array
     */
    public function getReportByDate(\DateTime $from, \DateTime $to, array $currencies): array
    {
        $response = $this->getEntityManager()->createQueryBuilder()
            ->select('r.code, AVG(r.rate / r.amount) average, MIN(r.rate / r.amount) as minValue, MAX(r.rate / r.amount) as maxValue')
            ->from(Rate::class, 'r')
            ->where('r.date >= :from')
            ->andWhere('r.date <= :to')
            ->andWhere('r.code IN (:currencies)')
            ->setParameters(
                [
                    'from' => $from->format('Y-m-d'),
                    'to' => $to->format('Y-m-d'),
                    'currencies' => $currencies
                ]
            )
            ->groupBy('r.code')
            ->getQuery()
            ->getResult();

        return $this->serializer->deserialize(json_encode($response), CNBReport::class.'[]', 'json');
    }

//    /**
//     * @return Rate[] Returns an array of Rate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rate
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
