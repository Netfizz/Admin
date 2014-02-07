<?php namespace Netfizz\Admin\Controllers;

use \Illuminate\Routing\Controller;
use View;

class BaseController extends Controller {

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
    }


    /**
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function index()
    {
        //return View::make( 'laravel-bootstrap::'.$this->view_key.'.index' )
        //    ->with( 'items' , $this->model->getAll() );

        return 'admin...';
    }



}