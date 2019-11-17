<?php
namespace BennoThommo\ForumPlus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateVotesTable extends Migration
{
    public function up()
    {
        Schema::create('bennothommo_forumplus_votes', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('voteable_type')->nullable();
            $table->integer('voteable_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->boolean('positive')->default(true);
            $table->timestamps();

            $table->index('voteable_type');
            $table->index('voteable_id');
        });
    }

    public function down()
    {
        Schema::drop('bennothommo_forumplus_votes');
    }
}
