<?php
// src/Acme/StoreBundle/Repository/ProductRepository.php

namespace Acme\StoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{

  public function findAllOrderedByName()
  {
    $q = $this->getEntityManager()
      ->createQuery('
        SELECT p FROM AcmeStoreBundle:Product p, AcmeStoreBundle:Category c
        WHERE p.category = c.id
        ORDER BY p.id ASC')
      ->setHint("\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS", true);
    return $q->getResult();
  }
  
}