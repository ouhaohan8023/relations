<?php

namespace Ohh\Relation\App\Services;

use App\Models\User;

class UserService extends BaseService
{
    public static function getUserByIds($ids)
    {
        $sql = "FIELD(id ,".implode(",", $ids->toArray()).")";
        return User::query()
            ->whereIn("id", $ids)
            ->orderByRaw($sql)
            ->get();
    }
}
