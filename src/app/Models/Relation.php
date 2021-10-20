<?php


namespace Ohh\Relation\App\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public $table = "relations";

    public function scopeLevel($query)
    {
        return $query->where("level", ">", 0);
    }

    public function getTable()
    {
        return config("relationship.relation_table");
    }
}
