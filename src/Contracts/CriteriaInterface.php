<?php

namespace Lakshmaji\Repository\Contracts;

use Lakshmaji\Repository\Criteria\Criteria;

/**
 * -----------------------------------------------------------------------------
 *   Criteria Interface
 * -----------------------------------------------------------------------------
 *
 * @package  repository
 * @since    1.0.0
 * @version  1.0.0
 * @author   Lakshmaji 
 */
interface CriteriaInterface
{

    /**
     * @param   bool $status
     * @return  $this
     */
    public function skipCriteria($status = true);

    /**
     * @return  mixed
     */
    public function getCriteria();

    /**
     * @param  Criteria $criteria
     * @param  $this
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * @param  Criteria $criteria
     * @param  $this
     */
    public function pushCriteria(Criteria $criteria);

    /**
     *
     */
    public function applyCriteria();
}
//end of interface CriteriaInterface
//end of file CriteriaInterface.php
