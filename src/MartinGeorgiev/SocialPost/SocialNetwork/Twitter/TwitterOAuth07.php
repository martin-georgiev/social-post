<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\SocialNetwork\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use MartinGeorgiev\SocialPost\Message;
use MartinGeorgiev\SocialPost\Publisher;
use MartinGeorgiev\SocialPost\SocialNetwork\Enum;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\MessageNotIntendedForPublisher;

/**
 * Provider for publishing on a Twitter page.
 * Uses TwitterOAuth v0.7
 * @see https://github.com/abraham/twitteroauth
 *
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class TwitterOAuth07 implements Publisher
{
    /**
     * @var TwitterOAuth
     */
    private $twitter;

    /**
     * @param TwitterOAuth $twitter Ready to use instance of TwitterOAuth
     */
    public function __construct(TwitterOAuth $twitter)
    {
        $this->twitter = $twitter;
    }

    public function canPublish(Message $message): bool
    {
        $canPublish = !empty(array_intersect($message->getNetworksToPublishOn(), [Enum::ANY, Enum::TWITTER]));

        return $canPublish;
    }

    public function publish(Message $message): bool
    {
        if (!$this->canPublish($message)) {
            throw new MessageNotIntendedForPublisher(Enum::TWITTER);
        }

        try {
            $status = $this->prepareStatus($message);
            $post = $this->twitter->post('statuses/update', ['status' => $status, 'trim_user' => true]);

            return !empty($post->id_str);
        } catch (\Exception $e) {
            throw new FailureWhenPublishingMessage($e);
        }
    }

    private function prepareStatus(Message $message): string
    {
        $status = $message->getMessage();

        if (filter_var($message->getLink(), FILTER_VALIDATE_URL) !== false) {
            $linkIsNotIncludedInTheStatus = mb_strpos($status, $message->getLink()) === false;
            if ($linkIsNotIncludedInTheStatus) {
                $status .= ' '.$message->getLink();
            }
        }

        return $status;
    }
}
