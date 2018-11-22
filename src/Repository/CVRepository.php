<?php

namespace App\Repository;

use App\Entity\Base\DiaUtil;
use App\Entity\CV;
use App\Repository\FilterRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Repository para a entidade DiaUtil.
 *
 * @author Carlos Eduardo Pauluk
 *
 */
class  CVRepository extends FilterRepository
{

    public function getEntityClass()
    {
        return CV::class;
    }

}
