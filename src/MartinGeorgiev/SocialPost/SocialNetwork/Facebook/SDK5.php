<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\SocialNetwork\Facebook;

use Facebook\Facebook;
use MartinGeorgiev\SocialPost\Message;
use MartinGeorgiev\SocialPost\Publisher;
use MartinGeorgiev\SocialPost\SocialNetwork\Enum;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\MessageNotIntendedForPublisher;

/**
 * Provider for publishing on a Facebook page. Uses Facebook PHP SDK v5.
 * @see https://developers.facebook.com/docs/php/Facebook/5.0.0
 *
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class SDK5 implements Publisher
{
    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * @var string
     */
    private $pageId;

    /**
     * @param Facebook $facebook Ready to use instance of the Facebook PHP SDK
     * @param string $pageId Identifier of the page, on which the status update will be published
     */
    public function __construct(Facebook $facebook, string $pageId)
    {
        $this->facebook = $facebook;
        $this->pageId = $pageId;
    }

    public function canPublish(Message $message): bool
    {
        $canPublish = !empty(array_intersect($message->getNetworksToPublishOn(), [Enum::ANY, Enum::FACEBOOK]));

        return $canPublish;
    }

    public function publish(Message $message): bool
    {
        if (!$this->canPublish($message)) {
            throw new MessageNotIntendedForPublisher(Enum::FACEBOOK);
        }

        try {
            $publishPostEndpoint = '/'.$this->pageId.'/feed';
            $response = $this->facebook->post(
                $publishPostEndpoint,
                $this->prepareParams($message)
            );
            $post = $response->getGraphNode();

            return isset($post['id']) ? !empty($post['id']) : false;
        } catch (\Exception $e) {
            throw new FailureWhenPublishingMessage($e);
        }
    }

    private function prepareParams(Message $message): array
    {
        $params = [];

        $params['message'] = $message->getMessage();

        if (filter_var($message->getLink(), FILTER_VALIDATE_URL) !== false) {
            $params['link'] = $message->getLink();
        }
        if (filter_var($message->getPictureLink(), FILTER_VALIDATE_URL) !== false) {
            $params['picture'] = $message->getPictureLink();
        }
        if (!empty($message->getCaption())) {
            $params['caption'] = $message->getCaption();
        }
        if (!empty($message->getDescription())) {
            $params['description'] = $message->getDescription();
        }

        return $params;
    }
}
