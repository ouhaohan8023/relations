<?php

return [
    "user_table" => env("RELATIONSHIP_USER_TABLE", "users"),

    "relation_table" => env("RELATIONSHIP_TABLE", "relations"),

    "parent_id_key" => env("RELATIONSHIP_PARENT_ID", "parent_id"),
];
