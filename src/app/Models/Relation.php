<?php


namespace Ohh\Relation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relation extends Model
{
    use SoftDeletes;
    public $table = "relations";

    protected $fillable = [
        "user_id",
        "parent_id",
        "level"
    ];

    public function scopeLevel($query)
    {
        return $query->where("level", ">", 0);
    }

    public function getTable()
    {
        return config("relationship.relation_table");
    }
}
