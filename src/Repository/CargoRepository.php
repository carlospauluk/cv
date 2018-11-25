<?php

namespace App\Repository;

use App\Entity\Base\DiaUtil;
use App\Entity\Cargo;

/**
 * Repository para a entidade Cargo.
 *
 * @author Carlos Eduardo Pauluk
 *
 */
class  CargoRepository extends FilterRepository
{

    public function getEntityClass()
    {
        return Cargo::class;
    }

}
