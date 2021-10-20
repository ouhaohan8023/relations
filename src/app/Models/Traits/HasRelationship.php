<?php


namespace Ohh\Relation\App\Models\Traits;

use Ohh\Relation\App\Models\Relation;

trait HasRelationship
{
    public function directChildren()
    {
        return $this->hasMany(self::class, config("relationship.parent_id_key"), "id");
    }

    public function directParent()
    {
        return $this->belongsTo(self::class, config("relationship.parent_id_key"), "id");
    }

    public function allChildren()
    {
        return $this
            ->hasMany(Relation::class, "user_id", "id")
            ->level();
    }

    public function allParents()
    {
        return $this
            ->hasMany(Relation::class, "parent_id", "id")
            ->level();
    }

    public function recursionChildren()
    {
        return $this
            ->hasMany(self::class, config("relationship.parent_id_key"), "id")
            ->with("recursionChildren");
    }

    public function sblings()
    {
        $key = config("relationship.parent_id_key");
        $parent = $this->$key;
        return self::query()->where($key, $parent)->where("id", "!=", $this->id)->get();
    }
}
