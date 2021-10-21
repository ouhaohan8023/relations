<?php

namespace Ohh\Relation\App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    /**
     * 通过给定的数组顺序获取用户
     * @param $ids
     */
    public static function getUserByIds($ids)
    {
        $sql = "FIELD(id ,".implode(",", $ids->toArray()).")";
        return User::query()
            ->whereIn("id", $ids)
            ->orderByRaw($sql)
            ->get();
    }

    /**
     * 转移节点，并同步更新子节点，调整层级关系
     * @param $userIds
     * @param $parentId
     * @return bool
     */
    public static function transfer($userIds, $parentId)
    {
        $key = config("relationship.parent_id_key");

        if (!is_array($userIds) && !($userIds instanceof Collection)) {
            $arr[] = $userIds;
            $userIds = $arr;
        }

        $bool = true;
        DB::beginTransaction();
        User::query()->whereIn("id", $userIds)->update([$key => $parentId]);
        RelationService::delWithoutSelf($userIds);
        $parents = RelationService::getRelationships($parentId);
        foreach ($parents as $p) {
            $add = RelationService::addRelationship($userIds, $p->parent_id, $p->level + 1);
            if (!$add) {
                $bool = false;
                DB::rollBack();
            }
        }
        DB::commit();
        foreach ($userIds as $userId) {
            $children = RelationService::getDirectChildIds($userId);
            self::transfer($children, $userId);
        }
        return $bool;
    }
}
