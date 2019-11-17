<?php
namespace BennoThommo\ForumPlus\Components;

use RainLab\Forum\Components\Channel as BaseComponent;
use RainLab\Forum\Models\Channel as ChannelModel;

class Channel extends BaseComponent
{
    public function componentDetails()
    {
        return [
            'name' => e(trans('rainlab.forum::lang.channel.component_name'))
                . ' (' . e(trans('bennothommo.forumplus::lang.plugin.suffix')) . ')',
            'description' => 'rainlab.forum::lang.channel.component_description',
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/rainlab/forum/assets/css/forum.css');

        $this->prepareVars();
        $this->page['channel'] = $this->getChannel();
        $this->page['isQA'] = (bool) $this->getChannel()->plusSettings['is_qa_channel'];

        return $this->prepareTopicList();
    }

    public function getChannel()
    {
        if ($this->channel !== null) {
            return $this->channel;
        }

        if (!$slug = $this->property('slug')) {
            return null;
        }

        return $this->channel = ChannelModel::with([
            'plusSettings'
        ])->whereSlug($slug)->first();
    }
}
