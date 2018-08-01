<?php

declare(strict_types=1);

namespace MartinGeorgiev\Tests\SocialPost\SocialNetwork\Exception;

use MartinGeorgiev\SocialPost\SocialNetwork\Exception\MessageNotIntendedForPublisher;
use PHPUnit\Framework\TestCase;

/**
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class MessageNotIntendedForPublisherTest extends TestCase
{
    /**
     * @test
     */
    public function is_exception(): void
    {
        $implementation = new MessageNotIntendedForPublisher('facebook');
        $this->assertInstanceOf(\DomainException::class, $implementation);
    }
}
