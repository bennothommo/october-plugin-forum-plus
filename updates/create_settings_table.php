<?php
namespace BennoThommo\ForumPlus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('bennothommo_forumplus_settings', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->nullable();
            $table->boolean('is_qa_channel')->default(false);
            $table->integer('qa_max_questions')->unsigned()->default(5);
            $table->integer('qa_auto_expire_days')->unsigned()->default(30);
            $table->integer('qa_max_accepted_answers')->unsigned()->default(1);
            $table->boolean('allow_voting')->default(false);
            $table->boolean('voting_allow_negative')->default(false);
            $table->boolean('voting_show_count_in_list')->default(false);
            $table->boolean('voting_on_all_posts')->default(false);
            $table->timestamps();

            $table->index('channel_id');
        });
    }

    public function down()
    {
        Schema::drop('bennothommo_forumplus_settings');
    }
}
