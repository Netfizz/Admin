<?php namespace Netfizz\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
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


    public function hydrateWithRelationship() {

        $attributes = $this->getAttributes();
        foreach($attributes as $attribute => $value) {

            // Auto sync relationship
            if (is_array($value)) {
                $this->syncRelationship($attribute, $value);
            }
        }

        return $this;
    }


    public function syncRelationship($relationshipName, $value)
    {

        $camelKey = camel_case($relationshipName);

        if (method_exists($this, $camelKey))
        {
            $relations = $this->$camelKey();

            if ($relations instanceof Relation)
            {
                $this->$camelKey()->sync($value);
                unset($this->$relationshipName);
            }
        }
    }

}