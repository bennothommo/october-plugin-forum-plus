<?php
namespace BennoThommo\ForumPlus\Models;

use Model;

class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'bennothommo_forumplus_settings';

    /**
     * Enables timestamping
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_qa_channel' => 'boolean',
        'qa_max_accepted_answers' => 'integer',
        'allow_voting' => 'boolean',
        'voting_allow_negative' => 'boolean',
        'voting_show_count_in_list' => 'boolean',
        'voting_on_all_posts' => 'boolean',
    ];

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    public $rules = [
        'is_qa_channel' => 'boolean',
        'qa_max_accepted_answers' => 'integer|min:0',
        'allow_voting' => 'boolean',
        'voting_allow_negative' => 'boolean',
        'voting_show_count_in_list' => 'boolean',
        'voting_on_all_posts' => 'boolean',
    ];

    /**
     * The array of custom attribute names.
     *
     * @var array
     */
    public $attributeNames = [
        'is_qa_channel' => 'bennothommo.forumplus::lang.fields.is_qa_channel.label',
        'qa_max_accepted_answers' => 'bennothommo.forumplus::lang.fields.qa_max_accepted_answers.label',
        'allow_voting' => 'bennothommo.forumplus::lang.fields.allow_voting.label',
        'voting_allow_negative' => 'bennothommo.forumplus::lang.fields.voting_allow_negative.label',
        'voting_show_count_in_list' => 'bennothommo.forumplus::lang.fields.voting_show_count_in_list.label',
        'voting_on_all_posts' => 'bennothommo.forumplus::lang.fields.voting_on_all_posts.label',
    ];
}
