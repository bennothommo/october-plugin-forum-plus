<?php
namespace BennoThommo\ForumPlus\Updates;

use BennoThommo\ForumPlus\Models\Settings;
use October\Rain\Database\Updates\Seeder;
use RainLab\Forum\Models\Channel;

class AttachCurrentChannels extends Seeder
{
    public function run()
    {
        foreach (Channel::get() as $channel) {
            Settings::create([
                'channel_id' => $channel->id,
            ]);
        }
    }
}
