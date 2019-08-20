<?php
declare(strict_types=1);

namespace StreamCake;

interface FeedManagerInterface
{
    /**
     * @param string $feed
     * @param string $id
     *
     * @return \GetStream\Stream\Feed
     */
    public function getFeed($feed, $id);

    /**
     * @param string $userId
     *
     * @return \GetStream\Stream\Feed
     */
    public function getUserFeed($userId);

    /**
     * @param string $userId
     *
     * @return \GetStream\Stream\Feed
     */
    public function getNotificationFeed($userId);

    /**
     * @param string $userId
     *
     * @return \GetStream\Stream\Feed[]
     */
    public function getNewsFeeds($userId);

    /**
     * @param string $userId
     * @param string $targetUserId
     */
    public function followUser($userId, $targetUserId);

    /**
     * @param string $userId
     * @param string $targetUserId
     */
    public function unfollowUser($userId, $targetUserId);
}
