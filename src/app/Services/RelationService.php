<?php

namespace Ohh\Relation\App\Services;

use Ohh\Relation\App\Models\Relation;

class RelationService extends BaseService
{
    public static function getChildIds($id, $orderBy = "asc")
    {
        return Relation::query()
            ->where("parent_id", $id)
            ->where("user_id", "!=", $id)
            ->orderBy("level", $orderBy)
            ->pluck("user_id");
    }

    public static function getParentIds($id, $orderBy = "asc")
    {
        return Relation::query()
            ->where("user_id", $id)
            ->where("parent_id", "!=", $id)
            ->orderBy("level", $orderBy)
            ->pluck("parent_id");
    }
}
