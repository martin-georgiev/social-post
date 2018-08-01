<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost;

/**
 * Main contract for publishing a new public message at a social network account
 *
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
interface Publisher
{
    /**
     * Tests if a message can be published with a social network.
     */
    public function canPublish(Message $message): bool;

    /**
     * Publishes a message to a social network.
     */
    public function publish(Message $message): bool;
}
