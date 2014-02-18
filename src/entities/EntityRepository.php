<?php namespace Netfizz\Entities;

use Chumper\Datatable\Datatable;
use Chumper\Datatable\Columns\FunctionColumn;
use DB;

class EntityRepository implements EntityRepositoryInterface {

    /**
     * @var Model entity
     */
    protected $model;

    protected $columns = array();

    protected $mainColumn;


    public function __construct()
    {

        //$this->primaryKeyName = $this->model->getKeyName();


        if ($this->mainColumn == null) {

            if (!isset($this->columns[0])) {
                // Force the execution to fail by throwing an exception:
                throw new RuntimeException('No columns defined in entity repository');
            }

            $this->mainColumn = $this->columns[0];
        }
    }

    public function setDatatable()
    {
        return Datatable::table()
            ->addColumn('')
            ->addColumn($this->getColumns())       // these are the column headings to be shown
            ->addColumn('Actions')
            //->setUrl(URL::action($this->getActionCtrl('getDatatableCollection')))   // this is the route where data will be retrieved
            ->setOptions('bStateSave', 'true'); // activate state history
            //->render();
    }


    public function getDatatableCollection()
    {

        return DB::table($this->model->getTable());

        var_dump($this->model);
        die;

        //var_dump(get_class_methods($this->model));
        //die;

        return $this->model->getDatatableCollection();
    }

    public function all()
    {

    }


    public function getColumns()
    {
        return $this->columns;
    }

    public function getMainColumn()
    {
        return $this->mainColumn;
    }

} 