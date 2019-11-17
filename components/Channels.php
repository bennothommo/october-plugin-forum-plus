<?php
namespace BennoThommo\ForumPlus\Components;

use RainLab\Forum\Components\Channels as BaseComponent;
use RainLab\Forum\Models\Channel;
use RainLab\Forum\Models\Member as MemberModel;
use RainLab\Forum\Classes\TopicTracker;

class Channels extends BaseComponent
{
    public function componentDetails()
    {
        return [
            'name' => e(trans('rainlab.forum::lang.channels.list_name'))
                . ' (' . e(trans('bennothommo.forumplus::lang.plugin.suffix')) . ')',
            'description' => 'rainlab.forum::lang.channels.list_desc',
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/rainlab/forum/assets/css/forum.css');

        $this->prepareVars();
        $this->page['channels'] = $this->listChannels();
    }

    public function listChannels()
    {
        if ($this->channels !== null) {
            return $this->channels;
        }

        $channels = Channel::with(
            'first_topic',
            'plusSettings'
        )->isVisible()->get();

        /*
         * Add a "url" helper attribute for linking to each channel
         */
        $channels->each(function ($channel) {
            $channel->setUrl($this->channelPage, $this->controller);

            if ($channel->first_topic) {
                $channel->first_topic->setUrl($this->topicPage, $this->controller);
            }
        });

        $this->page['member'] = $this->member = MemberModel::getFromUser();

        if ($this->member) {
            $channels = TopicTracker::instance()->setFlagsOnChannels($channels, $this->member);
        }

        $channels = $channels->toNested();

        return $this->channels = $channels;
    }
}
