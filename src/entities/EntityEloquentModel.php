<?php namespace Netfizz\Entities;

use Eloquent, DB;

class EntityEloquentModel extends Eloquent implements EntityModelInterface {

    // Use fillable as a white list
    //protected $fillable = array('username', 'email', 'password');

    // OR set guarded to an empty array to allow mass assignment of every field
    protected $guarded = array();


    public function getDatatableCollection()
    {
        return DB::table($this->getTable());
    }

}