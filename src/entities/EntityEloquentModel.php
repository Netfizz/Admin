<?php namespace Netfizz\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Collection;
use Eloquent, DB, Str;

class EntityEloquentModel extends Eloquent implements EntityModelInterface {

    // Use fillable as a white list
    //protected $fillable = array('username', 'email', 'password');

    // OR set guarded to an empty array to allow mass assignment of every field
    protected $guarded = array();

    //protected $purgeable = array();

    protected $relationsAttibutes = array();


    public function getDatatableCollection()
    {
        return DB::table($this->getTable());
    }


    protected static function boot()
    {
        parent::boot();

        static::bootRelationshipTraits();
    }

    public static function bootRelationshipTraits()
    {
        //$class = get_called_class();
        //var_dump('boot', class_basename($class));

        static::saving(function($instance) {
            $instance->fillRelationsAttributes();
            //$instance->purgeUnneeded();
        });

        static::saved(function($instance) {
            $instance->saveRelations();
        });
    }


    protected function fillRelationsAttributes()
    {
        foreach($this->attributes as $attribute => $value)
        {
            if ($relationObj = $this->isRelationshipProperty($attribute))
            {
                $this->setRelationsAttributes($attribute, $this->getAttribute($attribute));
                unset($this->attributes[$attribute]);
            }
        }
    }

    public function setRelationsAttributes($key, $value)
    {
        $this->relationsAttibutes[$key] = $value;
    }

    public function saveRelations()
    {
        foreach($this->relationsAttibutes as $attribute => $collection)
        {
            $relationObj = $this->$attribute();

            if (is_array($collection) && is_array(current($collection)))
            {
                $relatedModel = $relationObj->getRelated();
                $keyName = $relatedModel->getKeyName();
                $previousCollection = $relationObj->getResults();

                $keepItemsIds = array();

                // Update existing items and add new
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

                // delete old relationship
                $deleteIds = array_diff($previousCollection->modelKeys(), $keepItemsIds);
                if ( count($deleteIds) > 0 )
                {
                    $relatedModel->destroy($deleteIds);
                }
            }


            else
            {
                $relationObj->sync($collection);
            }
        }
    }


    public static function isRelationshipProperty($attribute)
    {
        if ( ! method_exists(get_called_class(), $attribute)) {
            return false;
        }

        // if this method return an eloquent Relationships class
        $relationObj = self::$attribute();
        if (is_subclass_of($relationObj, 'Illuminate\Database\Eloquent\Relations\Relation')) {
            return $relationObj;
        }

        return false;
    }



    /*
    public function __call($name, $arguments)
    {
        // Note : la valeur de $name est sensible à la casse.
        echo "Appel de la méthode '$name' "
            . implode(', ', $arguments). "\n";
    }

    public static function __callStatic($name, $arguments)
    {
        // Note : la valeur de $name est sensible à la casse.
        echo "Appel de la méthode statique '$name' "
            . implode(', ', $arguments). "\n";
    }
    */








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