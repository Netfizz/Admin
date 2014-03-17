<?php
/**
 * Created by PhpStorm.
 * User: bmatt
 * Date: 17/03/2014
 * Time: 11:55
 */

if ( ! function_exists('controllerAction'))
{
    /**
     * Generate a URL to current controller action.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @return string
     */
    function controllerAction($name, $parameters = array())
    {
        // get current controller name
        $controllerName = head(Str::parseCallback(Route::currentRouteAction(), null));

        // concatenate new action name
        $action = $controllerName . '@' . $name;

        return app('url')->action($action, $parameters);
    }
}