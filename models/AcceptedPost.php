<?php
namespace BennoThommo\ForumPlus\Models;

use Model;

class AcceptedPost extends Model
{
    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'bennothommo_forumplus_accepted_posts';

    /**
     * Enables timestamping
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic_id',
        'post_id'
    ];

    /**
     * Define relationships
     */
    public $belongsTo = [
        'topic' => [
            'RainLab\Forum\Models\Topic',
        ],
        'post' => [
            'RainLab\Forum\Models\Post',
        ],
    ];
}
