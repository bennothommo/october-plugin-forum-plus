<?php
namespace BennoThommo\ForumPlus\Components;

use Flash;
use Request;
use Redirect;
use October\Rain\Exception\ApplicationException;
use RainLab\Forum\Components\Topic as BaseComponent;
use RainLab\Forum\Models\Channel as ChannelModel;
use RainLab\Forum\Models\Post as PostModel;
use RainLab\Forum\Classes\TopicTracker;

class Topic extends BaseComponent
{
    /**
     * @var Collection Accepted posts cache for Twig access.
     */
    public $acceptedPosts = null;

    /**
     * Is the current topic a Q&A topic?
     *
     * @var boolean
     */
    public $isQA = false;

    /**
     * Is the current topic at the maximum number of accepted answers?
     *
     * @var boolean
     */
    public $maxAceptedAnswers = false;

    public function componentDetails()
    {
        return [
            'name' => e(trans('rainlab.forum::lang.topicpage.name'))
                . ' (' . e(trans('bennothommo.forumplus::lang.plugin.suffix')) . ')',
            'description' => 'rainlab.forum::lang.topicpage.self_desc',
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/rainlab/forum/assets/css/forum.css');
        $this->addCss('assets/css/forum-plus.css');
        $this->addJs('/plugins/rainlab/forum/assets/js/forum.js');

        $this->prepareVars();
        $this->handleOptOutLinks();

        if ($this->isQA) {
            return $this->prepareQA();
        } else {
            return $this->preparePostList();
        }
    }

    public function onAcceptAnswer()
    {
        $post = PostModel::find(post('post'));

        if (!$post) {
            throw new ApplicationException('Invalid answer.');
        }

        if (!$post->canAccept()) {
            throw new ApplicationException('You cannot accept an answer for this question.');
        }

        if ($post->accept()) {
            Flash::success('Answer successfully accepted.');
        } else {
            Flash::error('Unable to accept answer.');
        }

        $this->prepareVars();
        $this->prepareQA();
    }

    public function onUnacceptAnswer()
    {
        $post = PostModel::find(post('post'));

        if (!$post) {
            throw new ApplicationException('Invalid answer.');
        }

        if (!$post->canUnaccept()) {
            throw new ApplicationException('You cannot unaccept this answer.');
        }

        if ($post->unaccept()) {
            Flash::success('Answer successfully unaccepted.');
        } else {
            Flash::error('Unable to unaccept answer.');
        }

        $this->prepareVars();
        $this->prepareQA();
    }

    public function getChannel()
    {
        if ($this->channel !== null) {
            return $this->channel;
        }

        if ($topic = $this->getTopic()) {
            $channel = $topic->channel;
        } elseif ($channelId = input('channel')) {
            $channel = ChannelModel::with([
                'plusSettings'
            ])->find($channelId);
        } else {
            $channel = null;
        }

        // Add a "url" helper attribute for linking to the category
        if ($channel) {
            $channel->setUrl($this->channelPage, $this->controller);
        }

        return $this->channel = $channel;
    }

    public function getAcceptedPosts()
    {
        if ($this->acceptedPosts !== null) {
            return $this->acceptedPosts;
        }

        $topic = $this->getTopic();

        if ($topic === null || !count($this->getTopic()->acceptedPosts)) {
            return [];
        }

        $acceptedPosts = PostModel::with([
            'topic.channel.plusSettings',
            'member.user.avatar',
            'acceptedPost',
        ])
        ->whereIn('id', $this->getTopic()->acceptedPosts->pluck('post_id'))
        ->orderBy('created_at', 'asc')
        ->get();

        $acceptedPosts->each(function ($post) {
            if ($post->member) {
                $post->member->setUrl($this->memberPage, $this->controller);
            }
        });

        return $this->acceptedPosts = $acceptedPosts;
    }

    protected function prepareVars()
    {
        $this->page['memberPage'] = $this->memberPage = $this->property('memberPage');
        $this->page['channelPage'] = $this->channelPage = $this->property('channelPage');
        $this->page['topic'] = $this->getTopic();
        $this->page['channel'] = $this->getChannel();
        $this->page['member'] = $this->getMember();
        $this->page['isQA'] = $this->isQA = $this->getChannel()->plusSettings->is_qa_channel;
        $this->page['maxAcceptedAnswers'] = $this->maxAceptedAnswers = $this->atMaxAcceptedAnswers();
    }

    protected function prepareQA()
    {
        // If topic exists, load the posts
        if ($topic = $this->getTopic()) {
            // Store the initial question
            $firstPost = $this->getTopic()->first_post;
            if ($firstPost->member) {
                $firstPost->member->setUrl($this->memberPage, $this->controller);
            }
            $this->page['question'] = $firstPost;
            $this->page['acceptedAnswers'] = $acceptedPosts = $this->getAcceptedPosts();

            // Paginate all other answers
            $currentPage = input('page');
            $searchString = trim(input('search'));
            $ignoredIds = (count($acceptedPosts))
                ? array_merge([$firstPost->id], $acceptedPosts->pluck('id')->toArray())
                : [$firstPost->id];
            $posts = PostModel::with([
                'topic.channel.plusSettings',
                'member.user.avatar',
                'acceptedPost',
            ])
            ->whereNotIn('id', $ignoredIds)
            ->listFrontEnd([
                'page'    => $currentPage,
                'perPage' => $this->property('postsPerPage'),
                'sort'    => 'created_at',
                'topic'   => $topic->id,
                'search'  => $searchString,
            ]);

            $posts->each(function ($post) {
                // Determine if post is acceptable

                // Add a "url" helper attribute for linking to each member
                if ($post->member) {
                    $post->member->setUrl($this->memberPage, $this->controller);
                }
            });

            $this->page['posts'] = $this->posts = $posts;

            // Pagination
            $queryArr = [];
            if ($searchString) {
                $queryArr['search'] = $searchString;
            }
            $queryArr['page'] = '';
            $paginationUrl = Request::url() . '?' . http_build_query($queryArr);

            $lastPage = $posts->lastPage();
            if ($currentPage == 'last' || $currentPage > $lastPage && $currentPage > 1) {
                return Redirect::to($paginationUrl . $lastPage);
            }

            $this->page['paginationUrl'] = $paginationUrl;
        }

        // Set topic as watched
        if ($this->topic && $this->member) {
            TopicTracker::instance()->markTopicTracked($this->topic);
        }

        // Return URL
        if ($this->getChannel()) {
            if ($this->embedMode == 'single') {
                $returnUrl = null;
            } elseif ($this->embedMode) {
                $returnUrl = $this->currentPageUrl([$this->paramName('slug') => null]);
            } else {
                $returnUrl = $this->channel->url;
            }

             $this->returnUrl = $this->page['returnUrl'] = $returnUrl;
        }
    }

    protected function atMaxAcceptedAnswers()
    {
        $maxAnswers = $this->getChannel()->plusSettings->qa_max_accepted_answers;

        if ($maxAnswers === 0) {
            return false;
        }

        return count($this->getAcceptedPosts()) >= $maxAnswers;
    }
}
