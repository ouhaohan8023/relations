<?php


namespace Ohh\Relation\App\Models\Traits;

use Illuminate\Support\Facades\DB;
use Ohh\Relation\App\Services\RelationService;
use Ohh\Relation\App\Services\UserService;

trait HasRelationship
{
    protected $callProtectFunctions = ['allChildren', 'allParents', 'transfer', 'delNode'];

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

    /**
     * 获取当前节点的直接子级
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function directChildren()
    {
        return $this->hasMany(self::class, config("relationship.parent_id_key"), "id");
    }

    /**
     * 获取当前节点的直接父级
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function directParent()
    {
        return $this->belongsTo(self::class, config("relationship.parent_id_key"), "id");
    }

    /**
     * 已当前节点为根结点，向下遍历生成树 O(N), 注意使用缓存优化
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recursionChildren()
    {
        return $this
            ->hasMany(self::class, config("relationship.parent_id_key"), "id")
            ->with("recursionChildren");
    }

    /**
     * 获取当前节点的所有兄弟节点
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sblings()
    {
        return $this
            ->hasMany(self::class, config("relationship.parent_id_key"), config("relationship.parent_id_key"))
            ->where("id", "!=", $this->id);
    }

    /**
     * 获取指定节点/当前节点的所有子节点
     * @param  null  $userId
     * @param  string  $orderBy
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function allChildren($userId = null, $orderBy = "asc")
    {
        if (!$userId) {
            $userId = $this->id;
        }
        $ids = (new RelationService($this->relationModel))->getChildIds($userId, $orderBy);
        return (new UserService(self::class))->getUserByIds($ids);
    }

    /**
     * 获取指定节点/当前节点的所有父节点
     * @param  null  $userId
     * @param  string  $orderBy
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function allParents($userId = null, $orderBy = "asc")
    {
        if (!$userId) {
            $userId = $this->id;
        }
        $ids = (new RelationService($this->relationModel))->getParentIds($userId, $orderBy);
        return (new UserService(self::class))->getUserByIds($ids);
    }

    /**
     * 转移节点，并同步更新子节点，调整层级关系
     * 将 $userId 转移成 $parentId 节点的子节点
     * @param $parentId int 目标节点id
     * @param  null  $userId int 待转移的节点id
     * @return bool
     */
    protected function transfer($parentId, $userId = null)
    {
        if (!$userId) {
            $userId = $this->id;
            $oldParentId = $this->parent_id;
        } else {
            $user = self::find($userId);
            $oldParentId = $user->parent_id;
        }
        // skip if not change
        if ($oldParentId === $parentId) {
            return false;
        }
        // skip if same node
        if ($parentId === $userId) {
            return false;
        }
        return (new UserService(self::class))->transfer($userId, $parentId);
    }

    /**
     * 删除节点
     * @param  null  $userId int 删除节点id
     */
    protected function delNode($userId = null)
    {
        if (!$userId) {
            $userId = $this->id;
        }

        DB::beginTransaction();
        // del relationships
        (new RelationService($this->relationModel))->delNode($userId);

        // update parent_id column
        (new UserService(self::class))->delNode($userId);
        DB::commit();
    }
}
