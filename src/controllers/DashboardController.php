<?php namespace Netfizz\Admin\Controllers;

use View;

class DashboardController extends BaseController {

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
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function index()
    {
        //return View::make( 'laravel-bootstrap::'.$this->view_key.'.index' )
        //    ->with( 'items' , $this->model->getAll() );

        return View::make('admin::dashboard' )
            ->with( 'items' , range('a', 'z') )
            ->with('menu_items', array());

        //return 'admin... 23432';
    }



}