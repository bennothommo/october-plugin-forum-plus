<?php
return [
    'plugin' => [
        'name' => 'Forum Plus',
        'description' => 'Drop-in enhancement for the Forum plugin by RainLab.',
        'suffix' => 'Plus',
    ],
    'columns' => [
        'is_qa_channel' => 'Q&A Channel?',
        'allow_voting' => 'Allow Voting?',
    ],
    'tabs' => [
        'channel' => 'Channel',
        'qa' => 'Q&A',
        'voting' => 'Voting',
    ],
    'hints' => [
        'qa' => 'Questions & Answers channels allow users to post questions to the channel, and allow the community to
                 provide answers. Users who post questions can then accept the answer that solved their question,
                 allowing others in the community to see which answer helped them out.',
        'voting' => 'Voting allows forum users to approve (or even unapprove) topics or questions. You can also
                     optionally allow for replies or answers to also be voted on.',
    ],
    'fields' => [
        'is_qa_channel' => [
            'label' => 'This channel is for Questions & Answers?',
        ],
        'qa_max_accepted_answers' => [
            'label' => 'Maximum number of accepted answers',
            'comment' => 'Set to 0 to disable limit.',
        ],
        'allow_voting' => [
            'label' => 'Allow voting on channel threads?',
        ],
        'voting_allow_negative' => [
            'label' => 'Allow downvoting?',
            'comment' => 'Allows users to downvote or upvote. Only one type of vote will be accepted per thread / post.',
        ],
        'voting_show_count_in_list' => [
            'label' => 'Show vote count in thread list?',
        ],
        'voting_on_all_posts' => [
            'label' => 'Allow votes on all posts?',
            'comment' => 'If unchecked, voting will only be available on the initial post.',
        ],
    ],
];
