<?php namespace Netfizz\Admin\Controllers;

use Cviebrock\EloquentSluggable\Test\Post;
use \Illuminate\Routing\Controller;
use Chumper\Datatable\Datatable;
use Chumper\Datatable\Columns\FunctionColumn;

use View, URL, Form, DB, Redirect, Input, Validator, Route, Str, RuntimeException;

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

    //protected $primaryKeyName;

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
        $datatable = $this->repository->setDatatable();

        return View::make('admin::crud.index')->withDatatable($datatable);
    }


    public function getCreate()
    {
        $form = $this->repository->getForm();

        return View::make('admin::crud.edit', compact('form'));
    }

    public function putCreate()
    {
        if ($this->repository->store())
        {
            return Redirect::action($this->getActionCtrl('getIndex'))
                ->with('message', 'Item updated.');
        }

        return Redirect::action($this->getActionCtrl('getCreate'))
            ->withInput()
            //->withErrors($this->repository->getError())
            ->withErrors($this->repository->getValidator())
            ->with('message', 'There were validation errors.');
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

        //var_dump($item->getRelations());

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

        $form = $this->repository->getForm($item);

        return View::make('admin::crud.edit', compact('item', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function putEdit($id)
    {

        if ($this->repository->update($id))
        {
            return Redirect::action($this->getActionCtrl('getIndex'))
                ->with('message', 'Item updated.');
        }

        return Redirect::action($this->getActionCtrl('getEdit'), $id)
            ->withInput()
            ->withErrors($this->repository->getErrors())
            ->withWarnings($this->repository->getWarnings())
            ->with('message', 'There were validation errors.');

    }


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





    public function getDatatableCollection()
    {

        //$datas = DB::table($this->repository->getTable());

        $datas = $this->repository->getDatatableCollection();

        $datatable = new Datatable();
        $collection = $this->repository->all();

        //var_dump($collection, \Post::all());
        //die;

        return $datatable->collection($collection)
            //return $datatable->query($datas)
            //->addColumn($this->setSelectionColumn())
            ->showColumns($this->repository->getColumns())
            ->addColumn($this->setMainColumn())
            ->addColumn($this->setActionsColumn())
            //->searchColumns($this->columns)
            //->searchColumns('category')
            //->orderColumns($this->columns)
            ->make();
    }


    protected function setSelectionColumn()
    {
        $keyName = $this->repository->getKeyName();

        return new FunctionColumn('Selection', function($repository) use ($keyName)
        {
            return Form::checkbox('selected[]', $repository->{$keyName});
        });
    }

    protected function setActionsColumn()
    {
        $keyName = $this->repository->getKeyName();

        return new FunctionColumn('Actions', function($repository) use ($keyName)
        {
            $actions[] = link_to_action($this->getActionCtrl('getEdit'), 'Edit', $parameters = array($repository->{$keyName}), $attributes = array('class' => 'glyphicon glyphicon-edit', 'alt' => 'edit', 'title' => 'View'));
            $actions[] = link_to_action($this->getActionCtrl('getDestroy'), 'Del', $parameters = array($repository->{$keyName}), $attributes = array('class' => 'glyphicon glyphicon-remove', 'alt' => 'delete', 'title' => 'delete'));

            return implode('&nbsp; - &nbsp;', $actions);
        });
    }

    protected function setMainColumn($mainColumn = null)
    {

        if ($mainColumn === null) {
            $mainColumn = $this->repository->getMainColumn();
        }
        $keyName = $this->repository->getKeyName();

        // Add link to main column item
        return new FunctionColumn($mainColumn, function($repository) use ($mainColumn, $keyName)
        {
            return link_to_action($this->getActionCtrl('getShow'), $repository->{$mainColumn}, $parameters = array($repository->{$keyName}));
        });
    }

}