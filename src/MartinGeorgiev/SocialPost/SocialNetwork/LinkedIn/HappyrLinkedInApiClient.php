<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\SocialNetwork\LinkedIn;

use Happyr\LinkedIn\LinkedIn;
use MartinGeorgiev\SocialPost\Message;
use MartinGeorgiev\SocialPost\Publisher;
use MartinGeorgiev\SocialPost\SocialNetwork\Enum;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\MessageNotIntendedForPublisher;

/**
 * Provider for publishing on a LinkedIn page.
 * @see https://developer.linkedin.com/docs/company-pages
 * @see https://github.com/Happyr/LinkedIn-API-client
 *
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class HappyrLinkedInApiClient implements Publisher
{
    /**
     * @var LinkedIn
     */
    private $linkedIn;

    /**
     * @var string
     */
    private $companyPageId;

    /**
     * @param LinkedIn $linkedIn Ready to use instance of the Happyr's LinkedIn API client
     * @param string $accessToken Access token for a user with administrative rights of the page
     * @param string $companyPageId Identifier of the company page, on which the share will be published
     */
    public function __construct(LinkedIn $linkedIn, string $accessToken, string $companyPageId)
    {
        $linkedIn->setAccessToken($accessToken);
        $this->linkedIn = $linkedIn;
        $this->companyPageId = $companyPageId;
    }

    public function canPublish(Message $message): bool
    {
        $canPublish = !empty(array_intersect($message->getNetworksToPublishOn(), [Enum::ANY, Enum::LINKEDIN]));

        return $canPublish;
    }

    public function publish(Message $message): bool
    {
        if (!$this->canPublish($message)) {
            throw new MessageNotIntendedForPublisher(Enum::LINKEDIN);
        }

        try {
            $publishShareEndpoint = 'v1/companies/'.$this->companyPageId.'/shares';
            $options = ['json' => $this->prepareShareOptions($message)];
            $share = $this->linkedIn->post($publishShareEndpoint, $options);

            return isset($share['updateKey']) ? !empty($share['updateKey']) : false;
        } catch (\Exception $e) {
            throw new FailureWhenPublishingMessage($e);
        }
    }

    private function prepareShareOptions(Message $message): array
    {
        $share = [];

        $share['comment'] = $message->getMessage();
        $share['visibility']['code'] = 'anyone';

        if (filter_var($message->getLink(), FILTER_VALIDATE_URL) !== false) {
            $share['content']['submitted-url'] = $message->getLink();
        }
        if (filter_var($message->getPictureLink(), FILTER_VALIDATE_URL) !== false) {
            $share['content']['submitted-image-url'] = $message->getPictureLink();
        }
        if (!empty($message->getCaption())) {
            $share['content']['title'] = $message->getCaption();
        }
        if (!empty($message->getDescription())) {
            $share['content']['description'] = $message->getDescription();
        }

        return $share;
    }
}
