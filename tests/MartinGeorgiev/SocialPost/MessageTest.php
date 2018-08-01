<?php

declare(strict_types=1);

namespace MartinGeorgiev\Tests\SocialPost;

use MartinGeorgiev\SocialPost\SocialNetwork\Enum;
use MartinGeorgiev\SocialPost\Message;
use PHPUnit\Framework\TestCase;

/**
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class MessageTest extends TestCase
{
    public function data(): array
    {
        return [
            [
                '$networks' => [Enum::FACEBOOK],
                '$contents' => ['test message', 'https://www.example.com', 'https://www.example.com/logo.svg', 'test caption', 'test description'],
            ],
            [
                '$networks' => [Enum::ANY],
                '$contents' => ['test message', 'https://www.example.com', 'https://www.example.com/logo.svg', 'test caption'],
            ],
            [
                '$networks' => [Enum::TWITTER],
                '$contents' => ['test message', 'https://www.example.com', 'https://www.example.com/logo.svg'],
            ],
            [
                '$networks' => [Enum::FACEBOOK, Enum::TWITTER],
                '$contents' => ['test message', 'https://www.example.com'],
            ],
            [
                '$networks' => [Enum::ANY],
                '$contents' => ['test message'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider data
     */
    public function can_build_new_message(array $networks, array $contents): void
    {
        $message = new Message(...$contents);

        $this->assertEquals($contents[0], $message->getMessage());
        if (isset($contents[1])) {
            $this->assertEquals($contents[1], $message->getLink());
        }
        if (isset($contents[2])) {
            $this->assertEquals($contents[2], $message->getPictureLink());
        }
        if (isset($contents[3])) {
            $this->assertEquals($contents[3], $message->getCaption());
        }
        if (isset($contents[4])) {
            $this->assertEquals($contents[4], $message->getDescription());
        }

        $this->assertEquals([Enum::ANY], $message->getNetworksToPublishOn());
        $message->setNetworksToPublishOn($networks);
        $this->assertEquals($networks, $message->getNetworksToPublishOn());
    }
}
