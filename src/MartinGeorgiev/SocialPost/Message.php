<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost;

use MartinGeorgiev\SocialPost\SocialNetwork\Enum;

/**
 * Representation of a public message (status update) for a social network
 *
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class Message
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $pictureLink;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string[]
     *
     * @see Enum::ANY List of available networks
     */
    private $networksToPublishOn = [Enum::ANY];

    /**
     * @param string $message The main message
     * @param string $link Optional link to a web-page to display along the message
     * @param string $pictureLink Optional address of a picture to display along the message
     * @param string $caption Optional caption to display along the message
     * @param string $description Optional description to display along the message
     */
    public function __construct(
        string $message,
        string $link = '',
        string $pictureLink = '',
        string $caption = '',
        string $description = ''
    ) {
        $this->message = $message;
        $this->link = $link;
        $this->pictureLink = $pictureLink;
        $this->caption = $caption;
        $this->description = $description;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getPictureLink(): string
    {
        return $this->pictureLink;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string[] $networksToPublishOn
     *
     * @see Enum::ANY List of available networks
     */
    public function setNetworksToPublishOn(array $networksToPublishOn): self
    {
        $this->networksToPublishOn = $networksToPublishOn;

        return $this;
    }

    /**
     * @return string[]
     *
     * @see Enum::ANY List of available networks
     */
    public function getNetworksToPublishOn(): array
    {
        return $this->networksToPublishOn;
    }
}
