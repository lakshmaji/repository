<?php

namespace Lakshmaji\Repository\Criteria;

use Lakshmaji\Repository\Contracts\RepositoryInterface as Repository;

/**
 * -----------------------------------------------------------------------------
 *   Abstract Criteria class
 * -----------------------------------------------------------------------------
 *
 * @package  repository
 * @since    1.0.0
 * @version  1.0.0
 * @author   Lakshmaji 
 */
abstract class Criteria
{

    /**
     *
     * @param   $model
     * @param   RepositoryInterface  $repository
     * @return  mixed
     */
    abstract public function apply($model, Repository $repository);
}
//end of class Criteria
//end of file Criteria.php
