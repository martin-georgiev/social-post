<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\SocialNetwork;

use MartinGeorgiev\SocialPost\Message;
use MartinGeorgiev\SocialPost\Publisher;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\FailureWhenPublishingMessage;

/**
 * Publish simultaneously to all configured social networks
 *
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class AllInOne implements Publisher
{
    /**
     * @var Publisher[]
     */
    private $publishers = [];

    public function __construct(Publisher ...$publishers)
    {
        foreach ($publishers as $publisher) {
            $this->publishers[] = $publisher;
        }
    }

    public function canPublish(Message $message): bool
    {
        return true;
    }

    public function publish(Message $message): bool
    {
        try {
            $allPublished = true;
            foreach ($this->publishers as $publisher) {
                if ($publisher->canPublish($message)) {
                    $allPublished &= $publisher->publish($message);
                }
            }

            return (bool) $allPublished;
        } catch (\Exception $e) {
            throw new FailureWhenPublishingMessage($e);
        }
    }
}
