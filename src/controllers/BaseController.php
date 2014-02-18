<?php namespace Netfizz\Admin\Controllers;

use \Illuminate\Routing\Controller;
use Chumper\Datatable\Datatable;
use Chumper\Datatable\Columns\FunctionColumn;

use View, URL, Form, DB, Redirect, RuntimeException;

class BaseController extends Controller {

    /**
     * Model Repository
     *
     * @var repository
     */
    protected $repository;

    protected $columns = array();

    protected $mainColumn;

    protected $className;

    protected $primaryKeyName;

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    /**
     *
     */
    public function __construct()
    {
        // Setup composed views and the variables that they require
        //$this->beforeFilter( 'adminFilter' , array('except' => $this->whitelist) );
        $composed_views = array('admin::layout.*');
        View::composer($composed_views, 'Netfizz\Admin\Composers\Page');


        $this->className = get_called_class();


    }


    protected function getActionCtrl($action)
    {
        return $this->className . '@' . $action;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        /*
        if(Datatable::shouldHandle())
        {
            return $this->retrieveDatatableCollection();
        }


        $datatable = Datatable::table()
            ->addColumn('')
            ->addColumn($this->columns)       // these are the column headings to be shown
            ->addColumn('Actions')
            ->setUrl(URL::action($this->getActionCtrl('getDatatableCollection')))   // this is the route where data will be retrieved
            ->setOptions('bStateSave', 'true')
            ->render();
        */

        $datatable = $this->repository->setDatatable();

        $datatable->setUrl(URL::action($this->getActionCtrl('getDatatableCollection')));   // this is the route where data will be retrieved

        return View::make('admin::crud.index')->withDatatable($datatable);
    }

    public function getDatatableCollection()
    {

        //$datas = DB::table($this->repository->getTable());

        $datas = $this->repository->getDatatableCollection();


        //return Datatable::collection($this->repository->all($this->columns))
        return Datatable::query($datas)
            ->addColumn($this->setSelectionColumn())
            ->showColumns($this->repository->getColumns())
            ->addColumn($this->setMainColumn($this->repository->getMainColumn()))
            ->addColumn($this->setActionsColumn())
            ->searchColumns($this->columns)
            //->searchColumns('category')
            //->orderColumns($this->columns)
            ->make();
    }


    protected function setSelectionColumn()
    {
        //$primaryKeyName = $this->primaryKeyName;
        $primaryKeyName = 'id';

        return new FunctionColumn('Selection', function($repository) use ($primaryKeyName)
        {
            return Form::checkbox('selected[]', $repository->{$primaryKeyName});
        });
    }

    protected function setActionsColumn()
    {
        //$primaryKeyName = $this->primaryKeyName;
        $primaryKeyName = 'id';


        return new FunctionColumn('Actions', function($repository) use ($primaryKeyName)
        {
            $actions[] = link_to_action($this->getActionCtrl('getEdit'), 'Edit', $parameters = array($repository->{$primaryKeyName}), $attributes = array('class' => 'glyphicon glyphicon-edit', 'alt' => 'edit', 'title' => 'View'));
            $actions[] = link_to_action($this->getActionCtrl('getDestroy'), 'Del', $parameters = array($repository->{$primaryKeyName}), $attributes = array('class' => 'glyphicon glyphicon-remove', 'alt' => 'delete', 'title' => 'delete'));

            return implode('&nbsp; - &nbsp;', $actions);
        });
    }

    protected function setMainColumn($mainColumn)
    {

        //$primaryKeyName = $this->primaryKeyName;
        $primaryKeyName = 'id';


        // Add link to main column item
        return new FunctionColumn($mainColumn, function($repository) use ($mainColumn, $primaryKeyName)
        {
            return link_to_action($this->getActionCtrl('getShow'), $repository->{$mainColumn}, $parameters = array($repository->{$primaryKeyName}));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id)
    {
        $item = $this->repository->findOrFail($id);

        return $item;

        //return View::make('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id)
    {
        $item = $this->repository->find($id);

        if (is_null($item))
        {
            return Redirect::route('crud.index');
        }

        return View::make('admin::crud.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    /*
    public function update($id)
    {
        $input = array_except(Input::all(), '_method');
        $validation = Validator::make($input, Tweet::$rules);

        if ($validation->passes())
        {
            $country = $this->tweet->find($id);
            $country->update($input);

            return Redirect::route('tweets.show', $id);
        }

        return Redirect::route('tweets.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }
    */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $this->repository->find($id)->delete();

        return Redirect::action($this->getActionCtrl('getIndex'));
    }

}