<?php

namespace App\Repository;

use App\Utils\Repository\WhereBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Repository para a entidade Carteira.
 *
 * @author Carlos Eduardo Pauluk
 *
 */
abstract class FilterRepository extends ServiceEntityRepository
{

    private $logger;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, $this->getEntityClass());
    }

    /**
     * @return mixed
     */
    public function getLogger():? LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @required
     * @param mixed $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    abstract public function getEntityClass();

    public function handleFrombyFilters(QueryBuilder &$qb)
    {
        $qb->from($this->getEntityClass(), 'e');
    }

    public function findAll($orderBy = null)
    {
        return $this->findByFilters(null, $orderBy, $start = 0, $limit = null);
    }

    public function getDefaultOrders()
    {
        return array(
            ['column' => 'e.updated', 'dir' => 'desc']
        );
    }

    public function doCountByFilters($filters)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('count(e.id)');
        $this->handleFrombyFilters($qb);
        WhereBuilder::build($qb, $filters);
        $dql = $qb->getDql();
        $sql = $qb->getQuery()->getSQL();
        $count = $qb->getQuery()->getScalarResult();
        return $count[0][1];
    }

    /**
     *
     *
     * @param $filters
     * @param null $orders (no padrão do datatables.js)
     * @param int $start
     * @param int $limit
     * @return mixed
     * @throws \Exception
     */
    public function findByFilters($filters, $orders = null, $start = 0, $limit = 10)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e');
        $this->handleFrombyFilters($qb);
        WhereBuilder::build($qb, $filters);
        if (!$orders) {
            $orders = $this->getDefaultOrders();
        }
        if (is_array($orders)) {
            foreach ($orders as $order) {
                $qb->addOrderBy($order['column'], isset($order['dir']) ? $order['dir'] : 'asc');
            }
        } else if (is_string($orders)) {
            $qb->addOrderBy($orders, 'asc');
        }

        $dql = $qb->getDql();
        $sql = $qb->getQuery()->getSQL();
        $query = $qb->getQuery();
        $query->setFirstResult($start);
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query->execute();
    }
}
