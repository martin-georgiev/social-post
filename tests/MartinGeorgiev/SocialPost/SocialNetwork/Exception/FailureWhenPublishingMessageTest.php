<?php

declare(strict_types=1);

namespace MartinGeorgiev\Tests\SocialPost\SocialNetwork\Exception;

use MartinGeorgiev\SocialPost\SocialNetwork\Exception\FailureWhenPublishingMessage;
use PHPUnit\Framework\TestCase;

/**
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class FailureWhenPublishingMessageTest extends TestCase
{
    /**
     * @test
     */
    public function is_exception(): void
    {
        $exception = new \Exception('test exception');
        $implementation = new FailureWhenPublishingMessage($exception);
        $this->assertInstanceOf(\DomainException::class, $implementation);
    }
}
