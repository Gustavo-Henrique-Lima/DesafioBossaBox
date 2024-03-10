<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagToolTable extends Migration
{
    public function up()
    {
        Schema::create("tb_tag_tool", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("tag_id");
            $table->unsignedBigInteger("tool_id");
            $table->timestamps();

            $table->foreign("tag_id")->references("id")->on("tb_tags");
            $table->foreign("tool_id")->references("id")->on("tb_tools");
        });
    }

    public function down()
    {
        Schema::dropIfExists("tag_tool");
    }
}
