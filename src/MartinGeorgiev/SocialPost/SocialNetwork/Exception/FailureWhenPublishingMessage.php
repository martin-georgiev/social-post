<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\SocialNetwork\Exception;

/**
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class FailureWhenPublishingMessage extends \DomainException
{
    public function __construct(\Throwable $previous)
    {
        $message = sprintf('Cannot publish message. Last known error was: %s', $previous->getMessage());
        parent::__construct($message, 0, $previous);
    }
}
