<?php

declare(strict_types=1);

namespace MartinGeorgiev\Tests\SocialPost\SocialNetwork\LinkedIn;

use Happyr\LinkedIn\LinkedIn;
use MartinGeorgiev\SocialPost\Message;
use MartinGeorgiev\SocialPost\SocialNetwork\Enum;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\MessageNotIntendedForPublisher;
use MartinGeorgiev\SocialPost\SocialNetwork\LinkedIn\HappyrLinkedInApiClient;
use PHPUnit\Framework\TestCase;

/**
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class HappyrLinkedInApiClientTest extends TestCase
{
    /**
     * @test
     */
    public function can_publish_only_linkedin_intended_messages(): void
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        $message->setNetworksToPublishOn([Enum::LINKEDIN]);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertTrue($linkedInProvider->canPublish($message));
    }

    /**
     * @test
     */
    public function cannot_publish_when_message_not_intended_for_linkedin(): void
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        $message->setNetworksToPublishOn([Enum::FACEBOOK]);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertFalse($linkedInProvider->canPublish($message));
    }

    /**
     * @test
     */
    public function will_throw_an_exception_when_publishing_if_message_is_not_intended_for_linkedin(): void
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        $message->setNetworksToPublishOn([Enum::FACEBOOK]);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);

        $this->expectException(MessageNotIntendedForPublisher::class);
        $linkedInProvider->publish($message);
    }

    /**
     * @test
     */
    public function can_successfully_publish_a_share(): void
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $endpoint = sprintf('v1/companies/%s/shares', $companyPageId);

        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $share = 'test share';
        $link = 'https://www.example.com';
        $pictureLink = 'https://www.example.com/logo.svg';
        $caption = 'some caption';
        $description = 'some description';
        $message = new Message($share, $link, $pictureLink, $caption, $description);

        $data = ['json' => ['comment' => $share, 'visibility' => ['code' => 'anyone'], 'content' => ['submitted-url' => $link, 'submitted-image-url' => $pictureLink, 'title' => $caption, 'description' => $description]]];
        $linkedInResponse = ['updateKey' => '2017'];
        $linkedIn
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($linkedInResponse);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertTrue($linkedInProvider->publish($message));
    }

    /**
     * @test
     */
    public function will_fail_if_cannot_find_the_id_of_the_new_share(): void
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $endpoint = sprintf('v1/companies/%s/shares', $companyPageId);

        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $share = 'test share';
        $message = new Message($share);

        $data = ['json' => ['comment' => $share, 'visibility' => ['code' => 'anyone']]];
        $linkedInResponse = ['updateKey' => ''];
        $linkedIn
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($linkedInResponse);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertFalse($linkedInProvider->publish($message));
    }

    /**
     * @test
     */
    public function will_throw_an_exception_if_completely_fails_to_publish(): void
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        $linkedIn
            ->expects($this->once())
            ->method('post')
            ->willThrowException(new \Exception('something went wrong'));

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);

        $this->expectException(FailureWhenPublishingMessage::class);
        $linkedInProvider->publish($message);
    }
}
