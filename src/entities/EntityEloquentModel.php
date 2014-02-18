<?php namespace Netfizz\Entities;

use Eloquent, DB;

class EntityEloquentModel extends Eloquent implements EntityModelInterface {


    public function getDatatableCollection()
    {
        return DB::table($this->getTable());
    }

}