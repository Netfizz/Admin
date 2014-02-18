<?php namespace Netfizz\Entities;

use Eloquent;

class EntityEloquentModel extends Eloquent implements EntityModelInterface{

    /*
    public function all()
    {

    }
    */

    public function getDatatableCollection()
    {

        //get_class_methods()

        return 'paf';

        //return DB::table($this->getTable());
    }

}