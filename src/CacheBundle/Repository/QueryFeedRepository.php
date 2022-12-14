<?php

namespace CacheBundle\Repository;

use CacheBundle\Entity\Query\StoredQuery;
use Doctrine\ORM\EntityRepository;

/**
 * QueryFeedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QueryFeedRepository extends EntityRepository
{

    /**
     * Get single query feed from repository.
     *
     * @param integer $id   A Feed entity instance id.
     * @param integer $user Filter feeds by specified owner if set.
     *
     * @return \CacheBundle\Entity\Feed\AbstractFeed|null
     */
    public function getOne($id, $user)
    {
        $expr = $this->_em->getExpressionBuilder();
        $condition = $expr->andX($expr->eq('Feed.id', ':id'));
        $parameters = [ 'id' => $id ];

        if ($user !== null) {
            $condition->add($expr->eq('Feed.user', ':user'));
            $parameters['user'] = $user;
        }

        return $this->createQueryBuilder('Feed')
            ->where($condition)
            ->setParameters($parameters)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param integer $user A User entity id.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFeedsForFormQb($user)
    {
        return $this->createQueryBuilder('ch')
            ->where('ch.userId = :userId')
            ->setParameter(':userId', $user);
    }

    /**
     * @param integer $query A stored query instance.
     *
     * @return StoredQuery[]
     */
    public function getWithExcludedDocumentsForQuery($query)
    {
        return $this->createQueryBuilder('Feed')
            ->addSelect('partial Document.{id}')
            ->innerJoin('Feed.excludedDocuments', 'Document')
            ->where('Feed.query = :query')
            ->setParameter('query', $query)
            ->getQuery()
            ->getResult();
    }
}
