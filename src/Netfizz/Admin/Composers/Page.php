<?php namespace Netfizz\Admin\Composers;

use Config;

/*
namespace Davzie\LaravelBootstrap\Composers;
use Illuminate\Support\MessageBag;
use Auth, Session, Config, App;
*/
class Page{

    /**
     * Compose the view with the following variables bound do it
     * @param  View $view The View
     * @return null
     */
    public function compose($view)
    {
        /*
        $settings = App::make('Davzie\LaravelBootstrap\Settings\SettingsInterface');

        $view->with('user', Auth::user())
             ->with('app_name', $settings->getAppName() )
             ->with('urlSegment', Config::get('admin::app.access_url') )
             ->with('menu_items', Config::get('admin::app.menu_items') )
             ->with('success', Session::get('success' , new MessageBag ) );
        */

        $view->with('sitename', Config::get('admin::sitename'))
             ->with('menu_items', array());

        
    }

}