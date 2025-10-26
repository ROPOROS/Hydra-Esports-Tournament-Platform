<?php


namespace App\Repository;

use App\Entity\Tmatchs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */


class TmatchsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tmatchs::class);
    }


    public function findmymatch($idu) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT m from App\Entity\Tmatchs m where (m.idequipea = (SELECT t from App\Entity\Team t where t.captainid = :idu ) or m.idequipeb = (SELECT k from App\Entity\Team k where k.captainid = :idu )) and m.score = :nd")
                               ->setParameter('idu', $idu)
                               ->setParameter('nd', "nD");
                        
        return $query->getResult();
      }

      public function findallmatchs($idu) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT m from App\Entity\Tmatchs m where m.id !=  (SELECT p.idmatch from App\Entity\Pari p where p.iduser = :idu) " ) 
                               ->setParameter('idu', $idu);
                        
        return $query->getResult();
      }



      public function wewonourmatch($id, $score) {
        // TODO
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("UPDATE App\Entity\Tmatchs m SET m.score = :score where m.id = :id ")
                    ->setParameter('id', $id)
                    ->setParameter('score', $score);
        return $query->getResult();
      }

      function tri_asc_date()
      {
          return $this->createQueryBuilder('tmatchs')
              ->orderBy('tmatchs.datematch ','ASC')
              ->getQuery()->getResult();
      }
      function tri_desc_date()
      {
          return $this->createQueryBuilder('tmatchs')
              ->orderBy('tmatchs.datematch ','DESC')
              ->getQuery()->getResult();
      }
      function tri_asc_etat()
      {
          return $this->createQueryBuilder('tmatchs')
              ->orderBy('tmatchs.etat ','ASC')
              ->getQuery()->getResult();
      }
      function tri_desc_etat()
      {
          return $this->createQueryBuilder('tmatchs')
              ->orderBy('tmatchs.etat ','DESC')
              ->getQuery()->getResult();
      }

      function tri_asc_nomTournoi()
      {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT m from APP\Entity\Tmatchs m JOIN APP\Entity\Tournoi t where m.idtournoi = t.id order by t.nom ASC");
        return $query->getResult();
      }

      function tri_desc_nomTournoi()
      {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT m from APP\Entity\Tmatchs m JOIN APP\Entity\Tournoi t where m.idtournoi = t.id order by t.nom DESC");
        return $query->getResult();
      }

    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

?>