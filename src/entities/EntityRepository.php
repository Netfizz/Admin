<?php namespace Netfizz\Entities;

use Chumper\Datatable\Datatable;
//use Chumper\Datatable\Columns\FunctionColumn;
//use Illuminate\Support\Pluralizer;
use Netfizz\FormBuilder\FormBuilder;


use DB, Form, Input, Validator, RuntimeException;

class EntityRepository implements EntityRepositoryInterface {

    /**
     * @var Model entity
     */
    protected $model;

    protected $columns = array();

    protected $mainColumn;

    protected $validator;

    protected $rules = array();

    protected $error;

    protected $input;

    public function __construct(Validator $validator = null)
    {
        $this->validator = $validator;
    }


    /**
     * Retrieve model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }



    /**
     * Get all of the models from the database.
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }


    /**
     * Find a model by its primary key.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }


    /**
 	 * Find a model by its primary key or throw an exception.
     *
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }


    /**
     * Create the model to the database.
     *
     * @return bool
     */
    public function store()
    {
        if ( ! $this->validate())
        {
            return false;
        }

        $item = new $this->model;
        $item->create($this->getInput());

        return $item;
    }


    /**
     * Update the model to the database.
     *
     * @param $id
     * @return bool
     */
    public function update($id)
    {
        if ( ! $this->validate())
        {
            return false;
        }

        $item = $this->find($id);
        $item->update($this->getInput());

        return $item;
    }


    /**
     * Validate input
     *
     * @return bool
     */
    public function validate()
    {
        $validator = $this->getValidator();

        if ( ! $validator->passes())
        {
            $this->error = $validator->messages();
            return false;
        }

        return true;
    }

    /**
     * Retrieve Validator Entity
     * @return null
     */
    public function getValidator()
    {
        if ($this->validator === null) {
            $this->setValidator();
        }

        return $this->validator;
    }


    /**
     * Generate default validateor
     * @return \Illuminate\Validation\Validator
     */
    public function setValidator(Validator $validator = null)
    {
        $this->validator = $validator ?: Validator::make($this->getInput(), $this->getRules());
    }


    /**
     * Retrieve rules for validation
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }


    /**
     * Retrieve errors
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Retrieve inputs
     * @return null
     */
    public function getInput()
    {
        if ($this->input === null)
        {
            $this->setInput();
        }

        return $this->input;
    }


    /**
     * Set inputs
     */
    public function setInput($input = null)
    {
        $this->input = $input ?: array_except(Input::all(), '_method');
    }




    public function getForm($item = null)
    {
        $form = new FormBuilder($this->model);

        if ($item) {
            $form->populate($item);
        }

        return $form->getForm();
    }





    /**
     * @return table primary key name
     */
    public function getKeyName()
    {
        return $this->model->getKeyName();
    }





    public function setDatatable()
    {
        $datatable = new Datatable;
        return $datatable->table()
            //->addColumn(array('Selection' => 'Selection'))
            ->addColumn($this->getColumns())       // these are the column headings to be shown
            ->addColumn('Actions')

            // ->addColumn('Actionwxws')
            ->setUrl(controllerAction('getDatatableCollection'))   // this is the route where data will be retrieved
            ->setOptions('bStateSave', 'true'); // activate state history
        //->render();
    }


    public function getDatatableCollection()
    {
        return $this->model->getDatatableCollection();
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


} 