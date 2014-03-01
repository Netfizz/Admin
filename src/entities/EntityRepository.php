<?php namespace Netfizz\Entities;

use Chumper\Datatable\Datatable;
use Chumper\Datatable\Columns\FunctionColumn;
use DB, Form, RuntimeException;

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
        return $this->model->getDatatableCollection();
    }

    public function all()
    {

    }

    public function find($id)
    {
        return $this->model->find($id);
    }


    public function getColumns()
    {
        if (empty($this->columns))
        {
            // Force the execution to fail by throwing an exception:
            throw new RuntimeException('No columns defined in entity repository');
        }

        return $this->columns;
    }

    public function getMainColumn()
    {
        if ($this->mainColumn == null) {

            if (!isset($this->columns[0])) {
                // Force the execution to fail by throwing an exception:
                throw new RuntimeException('No columns defined in entity repository');
            }

            $this->mainColumn = $this->columns[0];
        }

        return $this->mainColumn;
    }

    public function getForm()
    {

        $form = Form::open();
        $form .= Form::text('username');

        $form .= Form::close();

        return $form;
    }

    /**
     * @return table primary key name
     */
    public function getKeyName()
    {
        return $this->model->getKeyName();
    }

} 