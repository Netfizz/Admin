<?php
/**
 * Created by PhpStorm.
 * User: bmatt
 * Date: 27/01/2014
 * Time: 16:55
 */

/**
 * Loggued routes without permission
 */
Route::group(array('prefix' => Config::get('admin::config.uri')), function()
{
    /*
    Route::get('/', array(
        'as' => 'indexDashboard',
        function(){
            return 'admin';
        })
    );
    */

    //     Route::resource('/', array('as' => 'dashboard', 'uses' => 'Netfizz\Admin\BaseController'));
    Route::resource('/', 'Netfizz\Admin\Controllers\DashboardController');
});
