<?php

namespace Ohh\Relation\App\Services;

use Ohh\Relation\App\Models\Relation;

class RelationService extends BaseService
{
    /**
     * 获取指定id的所有子级id
     * @param $id
     * @param  string  $orderBy asc 依次向下 / desc 从最底层往上
     * @return \Illuminate\Support\Collection
     */
    public static function getChildIds($id, $orderBy = "asc")
    {
        return Relation::query()
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
    public static function getParentIds($id, $orderBy = "asc")
    {
        return Relation::query()
            ->where("user_id", $id)
            ->where("parent_id", "!=", $id)
            ->orderBy("level", $orderBy)
            ->pluck("parent_id");
    }

    /**
     * 删除指定id的所有层级关系
     * @param $id
     */
    public static function del($id)
    {
        Relation::query()->where("user_id", $id)->delete();
    }

    /**
     * 删除指定id除自身外所有的层级关系
     * @param $id
     */
    public static function delWithoutSelf($id)
    {
        Relation::query()
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
    public static function getRelationships($id, $key = "user_id")
    {
        return Relation::query()->where($key, $id)->get();
    }

    /**
     * 添加层级关系， 同层级批量添加
     * @param $userIds
     * @param $parentId
     * @param $level
     * @return bool
     */
    public static function addRelationship($userIds, $parentId, $level)
    {
        $add = [];
        foreach ($userIds as $k => $userId) {
            $add[$k]["user_id"] = $userId;
            $add[$k]["parent_id"] = $parentId;
            $add[$k]["level"] = $level;
        }
        return Relation::insert($add);
    }

    /**
     * 获取下一级所有节点id
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public static function getDirectChildIds($id)
    {
        return Relation::query()
            ->where("parent_id", $id)
            ->where("level", 1)
            ->pluck("user_id");
    }
}
