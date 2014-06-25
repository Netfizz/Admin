<?php namespace Netfizz\Entities;

use Chumper\Datatable\Datatable;
//use Chumper\Datatable\Columns\FunctionColumn;
//use Illuminate\Support\Pluralizer;
use Netfizz\FormBuilder\FormBuilder;
use ArrayIterator;
use Country;


use DB, Form, Input, Validator, RuntimeException;

class EntityRepository implements EntityRepositoryInterface {

    /**
     * @var Model entity
     */
    protected $model;

    protected $columns = array();

    protected $mainColumn;

    protected $form;

    protected $formConfig;

    protected $validator;

    protected $rules = array();

    protected $messages = array();

    protected $errors;

    protected $warnings;

    protected $input;

    public function __construct(Validator $validator = null,  $form = null)
    {
        $this->validator = $validator;

        $this->form = $form;
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
        return $this->model->withTrashed()->find($id);
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
        $item->fill($this->getInput());
        $item->save();

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        //$item->hydrateWithRelationship()->save();

        //$item->create($this->getInput());

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

        $item = $this->findOrFail($id);
        $item->fill($this->getInput());



        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        //$item->hydrateWithRelationship();
        $item->save();

        // $item->push();

        //var_dump($item->blocks()->getResults()->toArray());
        //die;

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

        //var_dump($validator);
        //die;

        if ( ! $validator->passes())
        {
            $this->errors = $validator->messages();
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
        $this->validator = $validator ?: Validator::make(
            $this->getInput(),
            $this->getRules(),
            $this->getMessages()
        );
    }


    /**
     * Retrieve rules for validation
     * @return array
     */
    public function getRules()
    {
        foreach($this->rules as $name => $rules)
        {
            if (str_contains($name, '.'))
            {
                $this->setCollectionRules($name, $rules);
                unset($this->rules[$name]);
            }
        }

        //var_dump($this->rules);
        //die;

        return $this->rules;
    }


    protected function setCollectionRules($name, $rules)
    {
        $input = $this->getInput();
        $collection = explode('.', $name);

        /*
        for ($i = 0; $i < count($collection); $i++)
        {
            $segment = $collection[$i];
            $next = next($collection);

            $result = array_get($input, $segment, null);

            if (is_array($result) && $nb = count($result))
            {
                for ($a = 0; $a < $nb; $a++)
                {
                    $collection[] = $segment . '.' . $a . '.' . $next;
                }
            }
            elseif (is_string($result))
            {
                $this->rules[$segment] = $rules;
            }
        }
        */

        $iterator = new ArrayIterator($collection);
        while($iterator->valid())
        {
            $segment = $iterator->current();
            $result = array_get($input, $segment, null);

            if (is_array($result) && ! empty($result))
            {
                $nextIndex = $iterator->key() + 1;
                if ($iterator->offsetExists($nextIndex))
                {
                    $next = $iterator->offsetGet($nextIndex);
                    $iterator->offsetUnset($nextIndex);
                }

                foreach($result as $key => $value)
                {
                    // Check if entire collection row is empty
                    $value = array_filter($value);
                    if ( ! empty($value))
                    {
                        $iterator->append($segment . '.' . $key . '.' . $next);
                    }
                }
            }
            else
            {
                $this->rules[$segment] = $rules;
            }

            $iterator->next();
        }
    }


    public function getMessages()
    {
        return $this->messages;
    }


    public function getWarnings()
    {
        return $this->warnings;
    }


    /**
     * Retrieve errors
     *
     * @return mixed
     */
    public function getErrors()
    {

        //var_dump($this->errors->getMessages());

        return $this->errors;
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
     * Set Input
     * @param null $input
     */
    public function setInput($input = null)
    {
        $this->input = $input ?: array_except(Input::all(), '_method');
    }

    /**
     * Retrieve form
     *
     * @param null $item
     * @return mixed
     */
    public function getForm($model = null)
    {
        // Generate form if it doesn't exist
        if ($this->form === null)
        {
            $this->setForm();
        }

        if ($model)
        {
            /*
            $value = $item->getAttributes();
            $value['countries'] = $item->getAttribute('countries')->lists('id');
            $value['blocks'] = array(2, 3, 5);

            //var_dump($value, $item->getAttribute('countries')->lists('id'));

            $value = $item;
            */
            $this->form->populate($model);
        }
        else
        {
            $model = $this->getModel();
        }



        return $this->form->getForm($model);
    }


    /**
     * Set form or generate it based on his model
     * @param null $form
     */
    public function setForm($form = null)
    {
        $this->form = $form ?: new FormBuilder($this->formConfig, $this->model, $this->getValidator());
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