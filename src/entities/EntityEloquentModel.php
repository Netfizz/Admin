<?php namespace Netfizz\Entities;

use Netfizz\Traits\Model\RelationshipTrait;
use Eloquent, DB, Str;

class EntityEloquentModel extends Eloquent implements EntityModelInterface {

    use RelationshipTrait;

    // Use fillable as a white list
    //protected $fillable = array('username', 'email', 'password');

    // OR set guarded to an empty array to allow mass assignment of every field
    protected $guarded = array();


    protected $purgeable = array();



    public function getDatatableCollection()
    {
        return DB::table($this->getTable());
    }


    /**
     * Boot bootRelationshipTrait
     */
    protected static function boot()
    {
        parent::boot();

        static::bootRelationshipTrait();

        static::saving(function($instance) {
            $instance->purgeUnneeded();
        });
    }


     /**
     * Purges unneeded fields by getting rid of all attributes
     * ending in '_confirmation' or starting with '_'
     *
     * @return array
     */
    protected function purgeUnneeded()
    {
        $clean = array();
        foreach ($this->attributes as $key => $value)
        {
            if (! Str::endsWith($key, '_confirmation') && ! Str::startsWith($key, '_') && ! in_array($key, $this->purgeable))
                $clean[$key] = $value;
        }
        $this->attributes = $clean;
    }


}