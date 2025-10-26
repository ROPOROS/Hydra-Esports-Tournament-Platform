<?php

namespace App\Repository;

use App\Entity\Pari;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PariRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pari::class);
    }

    public function findbyiduser($id) {
      $entityManager = $this->getEntityManager();
      $query = $entityManager->createQuery("SELECT p from APP\Entity\pari p where p.iduser = :id")
                             ->setParameter('id', $id);
      return $query->getResult();
    }

    public function findbyiduserandidmatch($idu, $idm) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT p from APP\Entity\pari p where p.iduser = :id and p.idmatch = :idm")
                               ->setParameter('id', $idu)
                               ->setParameter('idm', $idm);
        return $query->getResult();
      }

      public function findbyidmatch($id) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT p from APP\Entity\pari p where p.idmatch = :id")
                               ->setParameter('id', $id);
        return $query->getResult();
      }

      public function findbyidmatchandwinningteam($id, $score) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT p from APP\Entity\pari p where p.idmatch = :id and p.idequipe = :score")
                               ->setParameter('id', $id)
                               ->setParameter('score', $score);
        return $query->getResult();
      }

      public function updateWallet($iduser, $winbet){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("UPDATE App\Entity\Joueur j set j.wallet = (j.wallet + :winbet ) where j.id = :iduser")
                    ->setParameter('winbet', $winbet)
                    ->setParameter('iduser', $iduser);
               return $query->getResult();
      }

      public function paywallet($iduser, $payment){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("UPDATE App\Entity\Joueur j set j.wallet = (j.wallet + :payment) where j.id = :iduser")
                    ->setParameter('payment', $payment)
                    ->setParameter('iduser', $iduser);
               return $query->getResult();
      }

      public function deleteparibackmoney($iduser, $price){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("UPDATE App\Entity\Joueur j set j.wallet = (j.wallet + :price) where j.id = :iduser ")
        ->setParameter('price', $price)
                    ->setParameter('iduser', $iduser);
               return $query->getResult();
      }

      public function removemoneyfromwallet($iduser, $price, $oldmontant){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("UPDATE App\Entity\Joueur j set j.wallet = ((j.wallet + :oldmontant) - :price) where j.id = :iduser ")
        ->setParameter('price', $price)
                    ->setParameter('iduser', $iduser)
                    ->setParameter('oldmontant', $oldmontant);
               return $query->getResult();
      }

      public function updateparibet($pari, $newmontant, $idequipe) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("UPDATE App\Entity\Pari p set p.montant = :newmontant , p.idequipe = :idequipe where p.iduser = :iduser and p.idmatch = :idmatch ")
        ->setParameter('newmontant', $newmontant)
                    ->setParameter('iduser', $pari[0]->getIdUser())
                    ->setParameter('idmatch', $pari[0]->getIdMatch())
                    ->setParameter('idequipe', $idequipe);
                    return $query->getResult();
      }

      function tri_asc_montant()
      {
          return $this->createQueryBuilder('pari')
              ->orderBy('pari.montant ','ASC')
              ->getQuery()->getResult();
      }
      function tri_asc_email()
      {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT p from APP\Entity\pari p JOIN APP\Entity\joueur j where p.iduser = j.id order by j.mail ASC");
        return $query->getResult();
      }

      function tri_desc_montant()
      {
          return $this->createQueryBuilder('pari')
              ->orderBy('pari.montant ','DESC')
              ->getQuery()->getResult();
      }

      function tri_desc_email()
      {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT p from APP\Entity\pari p JOIN APP\Entity\joueur j where p.iduser = j.id order by j.mail DESC");
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
