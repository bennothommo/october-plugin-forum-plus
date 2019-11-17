<?php
namespace BennoThommo\ForumPlus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAcceptedPostsTable extends Migration
{
    public function up()
    {
        Schema::create('bennothommo_forumplus_accepted_posts', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('topic_id')->unsigned()->nullable();
            $table->integer('post_id')->unsigned()->nullable();
            $table->timestamps();

            $table->index('topic_id');
            $table->index('post_id');
        });
    }

    public function down()
    {
        Schema::drop('bennothommo_forumplus_accepted_posts');
    }
}
