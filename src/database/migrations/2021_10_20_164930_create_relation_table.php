<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config("relationship.relation_table"), function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->comment("子级id");
            $table->integer("parent_id")->comment("父级id");
            $table->integer("level")->comment("两者间层级");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config("relationship.relation_table"));
    }
}
