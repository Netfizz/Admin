<?php namespace Netfizz\Entities;

use Eloquent;

class EntityMongoModel implements EntityModelInterface {

    public function getDatatableCollection()
    {

        return 'paf';

    }
}