<?php
namespace BennoThommo\ForumPlus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateVoteCountsTable extends Migration
{
    public function up()
    {
        Schema::create('bennothommo_forumplus_vote_counts', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('voteable_type')->nullable();
            $table->integer('voteable_id')->unsigned();
            $table->integer('positive')->unsigned()->default(0);
            $table->integer('negative')->unsigned()->default(0);

            $table->index('voteable_type');
            $table->index('voteable_id');
        });
    }

    public function down()
    {
        Schema::drop('bennothommo_forumplus_vote_counts');
    }
}
