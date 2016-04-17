<?php

namespace OC\PlatformBundle\Repository;

/**
 * ApplicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApplicationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getApplicationsWithAdverts($limit){
        $qb = $this->createQueryBuilder('app');
        
        $qb->join('app.advert', 'adv')
            ->addSelect('adv');
        $qb->setMaxResults($limit);

        return $qb->getQuery()
                ->getResult();
    }
}