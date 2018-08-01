<?php

declare(strict_types=1);

namespace MartinGeorgiev\Tests\SocialPost\SocialNetwork\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use MartinGeorgiev\SocialPost\Message;
use MartinGeorgiev\SocialPost\SocialNetwork\Enum;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\SocialNetwork\Exception\MessageNotIntendedForPublisher;
use MartinGeorgiev\SocialPost\SocialNetwork\Twitter\TwitterOAuth07;
use PHPUnit\Framework\TestCase;

/**
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/martin-georgiev/social-post
 */
class TwitterOAuth07Test extends TestCase
{
    /**
     * @test
     */
    public function can_publish_only_twitter_intended_messages(): void
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test message';
        $message = new Message($tweet);
        $message->setNetworksToPublishOn([Enum::TWITTER]);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->canPublish($message));
    }

    /**
     * @test
     */
    public function cannot_publish_when_message_not_intended_for_twitter(): void
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test message';
        $message = new Message($tweet);
        $message->setNetworksToPublishOn([Enum::FACEBOOK]);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertFalse($twitterProvider->canPublish($message));
    }

    /**
     * @test
     */
    public function will_throw_an_exception_when_publishing_if_message_is_not_intended_for_twitter(): void
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test message';
        $message = new Message($tweet);
        $message->setNetworksToPublishOn([Enum::FACEBOOK]);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);

        $this->expectException(MessageNotIntendedForPublisher::class);
        $twitterProvider->publish($message);
    }

    /**
     * @test
     */
    public function can_successfully_publish_a_tweet(): void
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $tweet = 'test tweet';
        $message = new Message($tweet);

        $endpoint = 'statuses/update';
        $data = ['status' => $tweet, 'trim_user' => true];
        $twitterResponse = (object) ['id_str' => '2007'];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->publish($message));
    }

    /**
     * @test
     */
    public function can_successfully_publish_a_tweet_with_a_link(): void
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $tweet = 'test tweet';
        $link = 'https://www.example.com';
        $message = new Message($tweet, $link);

        $endpoint = 'statuses/update';
        $status = $tweet.' '.$link;
        $data = ['status' => $status, 'trim_user' => true];
        $twitterResponse = (object) ['id_str' => '2007'];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->publish($message));
    }

    /**
     * @test
     */
    public function will_fail_if_cannot_find_the_id_of_the_new_tweet(): void
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $tweet = 'test tweet';
        $message = new Message($tweet);

        $endpoint = 'statuses/update';
        $data = ['status' => $tweet, 'trim_user' => true];
        $twitterResponse = (object) ['id_str' => ''];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertFalse($twitterProvider->publish($message));
    }

    /**
     * @test
     */
    public function will_throw_an_exception_if_completely_fails_to_publish(): void
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $tweet = 'test tweet';
        $message = new Message($tweet);
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->willThrowException(new \Exception('something went wrong'));

        $twitterProvider = new TwitterOAuth07($twitterOAuth);

        $this->expectException(FailureWhenPublishingMessage::class);
        $twitterProvider->publish($message);
    }
}
