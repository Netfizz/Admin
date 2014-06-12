<?php namespace Netfizz\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Collection;
use Eloquent, DB, Str;

class EntityEloquentModel extends Eloquent implements EntityModelInterface {

    // Use fillable as a white list
    //protected $fillable = array('username', 'email', 'password');

    // OR set guarded to an empty array to allow mass assignment of every field
    protected $guarded = array();


    protected $purgeable = array();


    public function getDatatableCollection()
    {
        return DB::table($this->getTable());
    }

//
//    public function hydrateWithRelationship() {
//
//        $attributes = $this->getAttributes();
//        foreach($attributes as $attribute => $value) {
//
//            // Auto sync relationship
//            if (is_array($value)) {
//                //var_dump($attribute, $value);
//                $this->syncRelationship($attribute, $value);
//
//            }
//        }
//
//        //die;
//
//        return $this;
//    }
//
//
//    public function syncRelationship($relationshipName, $value)
//    {
//
//        $camelKey = camel_case($relationshipName);
//
//        if (method_exists($this, $camelKey))
//        {
//            $this->purgeable[] = $relationshipName;
//
//            $relations = $this->$camelKey();
//
//
//            $relationType = class_basename(get_class($relations));
//
//            if ($relationType === 'MorphMany') {
//                //var_dump($camelKey, $relationType, $value, $relations);
//                /*
//                foreach ($this->relations as $models)
//                {
//                    foreach (Collection::make($models) as $model)
//                    {
//                        //if ( ! $model->push()) return false;
//                        var_dump($model);
//                    }
//                }
//                */
//
//
//            } else {
//                $this->$camelKey()->sync($value);
//                //unset($this->$relationshipName);
//            }
//
//
//
//            /*
//            if ($relations instanceof Relation)
//            {
//                //var_dump($camelKey, $this->{$camelKey}, $value, 'xxxxxxxxxxxxxxxx');
//                //$this->$camelKey()->sync($value);
//                //unset($this->$relationshipName);
//
//
//            }
//            */
//
//        }
//    }
//



    public function fill(array $attributes)
    {

        parent::fill($attributes);

        $this->fillRelations($attributes);

        return $this;
    }

    public function fillRelations(array $attributes)
    {
        $attributes = $this->getAttributes();

        foreach($attributes as $attribute => $collection)
        {

            if ($relationObj = $this->isRelationshipProperty($attribute))
            {
                $this->purgeable[] = $attribute;

                if (is_array($collection) && is_array(current($collection))) {

                    $relatedModel = $relationObj->getRelated();
                    $keyName = $relatedModel->getKeyName();
                    $previousCollection = $relationObj->getResults();

                    $keepItemsIds = array();

                    // Add new items and update existing items
                    foreach($collection as $item)
                    {
                        $check = array_filter($item);
                        if (empty($check))
                        {
                            continue;
                        }

                        if (array_key_exists($keyName, $item)
                            && $itemModel = $previousCollection->find($item[$keyName]))
                        {
                            $itemModel->update($item);
                        }
                        else
                        {
                            unset($item[$keyName]);
                            $itemModel = $relationObj->create($item);
                        }

                        $keepItemsIds[] = $itemModel->getKey();
                    }

                    // supprimer ancien
                    $deleteIds = array_diff($previousCollection->modelKeys(), $keepItemsIds);
                    if ( count($deleteIds) > 0 )
                    {
                        $relatedModel->destroy($deleteIds);
                    }
                }
            }
        }
    }


    protected function isRelationshipProperty($attribute)
    {
        if ( ! method_exists($this, $attribute)) {
            return false;
        }

        // if this method return an eloquent Relationships class
        $relationObj = $this->$attribute();
        if (is_subclass_of($relationObj, 'Illuminate\Database\Eloquent\Relations\Relation')) {
            return $relationObj;
        }

        return false;
    }


    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = array())
    {
        $this->purgeUnneeded();

        return parent::save($options);
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