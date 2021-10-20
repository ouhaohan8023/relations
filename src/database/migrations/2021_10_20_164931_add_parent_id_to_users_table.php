<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config("relationship.user_table"), function (Blueprint $table) {
            $table->integer(config("relationship.parent_id_key"))->default(0)->comment("父级ID");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config("relationship.user_table"), function (Blueprint $table) {
            $table->dropColumn(config("relationship.parent_id_key"));
        });
    }
}
