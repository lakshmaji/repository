<?php

namespace Lakshmaji\Repository\Eloquent;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Lakshmaji\Repository\Contracts\CriteriaInterface;
use Lakshmaji\Repository\Contracts\RepositoryInterface;
use Lakshmaji\Repository\Criteria\Criteria;
use Lakshmaji\Repository\Exceptions\RepositoryException;

/**
 * -----------------------------------------------------------------------------
 *   Repository class
 * -----------------------------------------------------------------------------
 *
 * @package  repository
 * @since    1.0.0
 * @version  1.0.0
 * @author   Lakshmaji 
 */
abstract class Repository implements RepositoryInterface, CriteriaInterface
{

    /**
     * Instance of App
     *
     * @var        App
     * @access    private
     * @since    v1.0.0
     */
    private $app;

    /**
     *
     * @var
     * @access    protected
     * @since    v1.0.0
     **/
    protected $model;

    /**
     * @var     Collection
     * @access  protected
     * @since   v1.0.0
     */
    protected $criteria;

    /**
     * @var  bool
     */
    protected $skipCriteria = false;

    //--------------------------------------------------------

    /**
     * Constructor Method
     *
     * @access    public
     * @param    App    $app
     * @throws    Apen\Repositories\Exceptions\RepositoryException
     * @since    1.0.0
     */
    public function __construct(App $app, Collection $collection)
    {
        $this->app = $app;
        $this->criteria = $collection;
        $this->resetScope();
        $this->makeModel();
    }

    //--------------------------------------------------------------------

    /**
     * Initializes the class variable model.
     *
     * @access    public
     * @return    Model
     * @throws    RepositoryException
     * @since    1.0.0
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        return $this->model = $model;
    }

    //--------------------------------------------------------------------

    /**
     * Specifies the model class name.
     *
     * @abstract
     * @return    mixed
     * @since    1.0.0
     **/
    abstract public function model();

    //--------------------------------------------------------------------

    /**
     * Returns all the records from the table.
     *
     * @access    public
     * @param     array   $columns
     * @return    mixed
     * @since     1.0.0
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->get($columns);
    }

    //--------------------------------------------------------------------

    /**
     * Writes information into the tables.
     *
     * @access  public
     * @param   array  $data
     * @return  mixed
     * @since   1.0.0
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    //--------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   array    $data
     * @param   integer  $id
     * @param   string   $attribute
     * @return  mixed
     * @since   1.0.0
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    //--------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   integer  $id
     * @return  mixed
     * @since   1.0.0
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    //--------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   integer $id
     * @param   array   $columns
     * @return  mixed
     * @since   1.0.0
     */
    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->find($id, $columns);
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $columns
     * @return  mixed
     * @since   1.0.0
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $columns
     * @return  mixed
     * @since   1.0.0
     */
    public function findMultipleBy($attribute, $value, $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->select($columns)->get();
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access  public
     * @return  $this
     * @since   1.0.0
     */
    public function resetScope()
    {
        $this->skipCriteria(false);
        return $this;
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   bool  $status
     * @return  $this
     * @since   1.0.0
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   return mixed
     * @since   1.0.0
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   Criteria $criteria
     * @return  mixed
     * @since   1.0.0
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access  public
     * @param   Criteria $criteria
     * @return  $this
     * @since   1.0.0
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criteria->push($criteria);
        return $this;
    }

    //-------------------------------------------------------------------

    /**
     *
     * @access   public
     * @return   $this
     * @since    1.0.0
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->model = $criteria->apply($this->model, $this);
            }
        }

        return $this;
    }

    //-------------------------------------------------------------------

    /**
     * Removes the rows matching the passed conditions.
     * https://codeload.github.com/BlackrockDigital/startbootstrap-new-age/zip/gh-pages
     * @access   public
     * @param    string   $column
     * @param    string   $value
     * @param    string   $condition
     * @since    1.0.0
     */
    public function deleteWhere($column, $value, $condition = '=')
    {
        return $this->model->where($column, $condition, $value)->delete();
    }
}
//end of class Repository
//end of file Repository.php
