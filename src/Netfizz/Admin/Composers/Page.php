<?php namespace Netfizz\Admin\Composers;

use Menu;
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

        /*
        $test = Config::get('admin::menus.main');
        //var_dump($test);

        $menu2 = Menu::handler('categories')
            ->add('algorithms', 'Algorithms', Menu::items()->prefixParents()
                ->add('cryptography', 'Cryptography')
                ->add('data-structures', 'Data Structures')
                ->add('digital-image-processing', 'Digital Image Processing')
                ->add('memory-management', 'Memory Management'))
            ->add('graphics-and-multimedia', 'Graphics & Multimedia', Menu::items()->prefixParents()
                ->add('directx', 'DirectX')
                ->add('flash', 'Flash')
                ->add('opengl', 'OpenGL'));

        $menu3 = Menu::handler('admin');

        $menu = Menu::handler('main')->setItemList('main', $test);

        var_dump($menu, $menu2);

        */



        // Assigne une référence à une variable statique
        $menu = Menu::handler(Config::get('admin::menu-name'))
            //->prefix(Config::get('admin::config.uri'))
            ->addClass('nav navbar-nav')
            ->setOption('item.active_child_class', 'active');



        $menu->getItemListsAtDepth(1)
             ->map(function($itemList)
             {
                 if($itemList->hasChildren())
                 {
                     $itemList->addClass('dropdown-menu');
                 }
             });


        $menu->getItemsByContentType('Menu\Items\Contents\Link')
            ->map(function($item)
            {
                if($item->isActive())
                {
                    $item->addClass('active');
                }


                if($item->hasChildren())
                {
                    // Add a class to the LI
                    $item->addClass('dropdown');

                    // Add a class to the A
                    $item->getContent()
                        ->addClass('dropdown-toggle')
                        ->setAttribute('data-toggle', 'dropdown');

                    // Add a class to the UL
                    $item->getChildren()
                        ->addClass('dropdown-menu');
                }


            });


        //$menu->prefix(Config::get('admin::config.uri'));

        //var_dump($menu->getContainer());
        //var_dump($menu->breadcrumbs());

        //$breadcrumbs = Menu::handler(Config::get('admin::menu-name'))

        /*
        $breadcrumbs = $menu
            ->breadcrumbs()->map(function($itemList)
            {
                //var_dump($itemList);
                $itemList->map(function($item) {
                   var_dump($item);
                });

            })
            //->prefix(Config::get('admin::config.uri'))
            //->setElement('ol')
            ->addClass('brexxadcrumb');
        */


        //var_dump($menu->breadcrumbs()->getAllItems());

        //die;

        /*
        $breadcrumbs = Menu::breadcrumbs(function($itemLists)
        {
            return $itemLists[1]; // returns first match
        })
            ->setElement('ol')
            ->addClass('breadcrumb');
        */

        $view->with('sitename', Config::get('admin::sitename'))
             //->with('breadcrumbs', $breadcrumbs->render())
             ->with('breadcrumbs', null)
             ->with('main_menu', $menu->render());

        
    }

}