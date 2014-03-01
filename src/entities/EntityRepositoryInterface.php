<?php namespace Netfizz\Entities;

interface EntityRepositoryInterface {
    /*
    public function create(array $attributes);

    public function all($columns = array('*'));

    public function find($id, $columns = array('*'));

    public function destroy($ids);
    */
    //public function all();

    public function getColumns();

    public function getKeyName();
} 