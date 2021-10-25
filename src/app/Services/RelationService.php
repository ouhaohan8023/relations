<?php

namespace Ohh\Relation\App\Services;

class RelationService extends BaseService
{
    /**
     * 获取指定id的所有子级id
     * @param $id
     * @param  string  $orderBy asc 依次向下 / desc 从最底层往上
     * @return \Illuminate\Support\Collection
     */
    public function getChildIds($id, $orderBy = "asc")
    {
        return $this->getClass()
            ->query()
            ->where("parent_id", $id)
            ->where("user_id", "!=", $id)
            ->orderBy("level", $orderBy)
            ->pluck("user_id");
    }

    /**
     * 获取指定id的所有父级id
     * @param $id
     * @param  string  $orderBy asc 依次向上 / desc 从最高层向下
     * @return \Illuminate\Support\Collection
     */
    public function getParentIds($id, $orderBy = "asc")
    {
        return $this->getClass()
            ->query()
            ->where("user_id", $id)
            ->where("parent_id", "!=", $id)
            ->orderBy("level", $orderBy)
            ->pluck("parent_id");
    }

    /**
     * 删除指定id的所有层级关系
     * @param $id
     */
    public function del($id)
    {
        $this->getClass()->query()->where("user_id", $id)->delete();
    }

    /**
     * 删除指定id除自身外所有的层级关系
     * @param $id
     */
    public function delWithoutSelf($id)
    {
        $this->getClass()
            ->query()
            ->whereIn("user_id", $id)
            ->whereNotIn("parent_id", $id)
            ->delete();
    }

    /**
     * 获取指定id，指定字段的关系
     * @param $id
     * @param  string  $key "user_id" | "parent_id"
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelationships($id, $key = "user_id")
    {
        return $this->getClass()->query()->where($key, $id)->get();
    }

    /**
     * 添加层级关系， 同层级批量添加
     * @param $userIds
     * @param $parentId
     * @param $level
     * @return bool
     */
    public function addRelationship($userIds, $parentId, $level)
    {
        $add = [];
        foreach ($userIds as $k => $userId) {
            $add[$k]["user_id"] = $userId;
            $add[$k]["parent_id"] = $parentId;
            $add[$k]["level"] = $level;
        }
        return $this->getClass()->query()->insert($add);
    }

    /**
     * 获取下一级所有节点id
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getDirectChildIds($id)
    {
        return $this->getClass()->query()
            ->where("parent_id", $id)
            ->where("level", 1)
            ->pluck("user_id");
    }

    public function upperNode($userId, $level)
    {
        $this->getClass()->query()
            ->where("user_id", $userId)
            ->where("level", ">", $level)
            ->decrement("level");
    }

    public function delNode($userId)
    {
        // del children
        $children = $this->getRelationships($userId, "parent_id");
        foreach ($children as $c) {
            if ($c->user_id != $c->parent_id) {
                $this->upperNode($c->user_id, $c->level);
                $c->delete();
            }
        }

        // del parents
        $this->del($userId);
    }

    public function getChildrenIdWithSelf($userId)
    {
        $children = $this->getChildIds($userId)->toArray();
        $children[] = $userId;
        return $children;
    }

    public function removeNode($children)
    {
        $this->getClass()->query()->whereIn("user_id", $children)->delete();
    }
}
