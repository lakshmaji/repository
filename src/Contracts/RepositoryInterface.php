<?php

namespace Lakshmaji\Repository\Contracts;

/**
 * -----------------------------------------------------------------------------
 *   Repository Interface
 * -----------------------------------------------------------------------------
 *
 * @package  repository
 * @since    1.0.0
 * @version  1.0.0
 * @author   Lakshmaji 
 */
interface RepositoryInterface
{

    public function all($columns = ['*']);

    /**
     * @param array $data
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param $id
     */
    public function update(array $data, $id);

    /**
     * @param $id
     */
    public function delete($id);

    /**
     * @param $id
     * @param array $columns
     */
    public function find($id, $columns = ['*']);

    /**
     * @param $field
     * @param $value
     * @param array $columns
     */
    public function findBy($field, $value, $columns = ['*']);
}
//end of interface RepositoryInterface
//end of file RepositoryInterface.php
