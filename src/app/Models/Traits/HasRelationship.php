<?php


namespace Ohh\Relation\App\Models\Traits;

use Ohh\Relation\App\Models\Relation;
use Ohh\Relation\App\Services\RelationService;
use Ohh\Relation\App\Services\UserService;

trait HasRelationship
{
    protected $callProtectFunctions = ['allChildren', 'allParents'];

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, array_merge([], $this->callProtectFunctions))) {
            return $this->$method(...$parameters);
        }

        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }

    public function directChildren()
    {
        return $this->hasMany(self::class, config("relationship.parent_id_key"), "id");
    }

    public function directParent()
    {
        return $this->belongsTo(self::class, config("relationship.parent_id_key"), "id");
    }

    public function recursionChildren()
    {
        return $this
            ->hasMany(self::class, config("relationship.parent_id_key"), "id")
            ->with("recursionChildren");
    }

    public function sblings()
    {
        return $this
            ->hasMany(self::class, config("relationship.parent_id_key"), config("relationship.parent_id_key"))
            ->where("id", "!=", $this->id);
    }

    protected function allChildren($userId=null, $orderBy = "asc")
    {
        if (!$userId) {
            $userId = $this->id;
        }
        $ids = RelationService::getChildIds($userId, $orderBy);
        return UserService::getUserByIds($ids);
    }

    protected function allParents($userId=null, $orderBy = "asc")
    {
        if (!$userId) {
            $userId = $this->id;
        }
        $ids = RelationService::getParentIds($userId, $orderBy);
//        return $ids;
        return UserService::getUserByIds($ids);
    }
}
