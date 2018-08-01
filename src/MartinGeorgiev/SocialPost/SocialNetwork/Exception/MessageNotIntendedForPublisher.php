<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\SocialNetwork\Exception;

/**
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class MessageNotIntendedForPublisher extends \DomainException
{
    public function __construct(string $socialNetwork)
    {
        $message = sprintf('The message is not intended to be published on %s', $socialNetwork);
        parent::__construct($message);
    }
}
