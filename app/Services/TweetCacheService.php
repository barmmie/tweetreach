<?php

namespace App\Services;
use App\Tweet;

class TweetCacheService {
    /**
     * Determines when the cache should be invalidated
     * 
     * @var string
     */
    const CACHE_DURATION_HOURS = 2;
    
    /**
     * The twitter service. to make
     * Makes authenticated api calls to the twitter endpoints.
     * 
     * @var \App\Services\TwitterService
     */
    private $twitterService;

    /**
     * Create a new TweetCache Service instance.
     *
     * @param  \App\Services\TweetCacheService  $twitterService
     * @return void
     */
    public function __construct(TwitterService $twitterService) {
         $this->twitterService = $twitterService;
    }
    
    /**
     * Gets the follower count for a tweet.
     *
     * @param  string  $tweet_id
     * @return string|int 
     */
    public function getTweet($tweet_id) {
        
        //If there is  a cached tweet simply return that
        if($this->hasCachedTweet($tweet_id)) {
            $tweet = $this->getCachedTweet($tweet_id);
            return $tweet->total_follower_count;
        }
        
        //If there isn't make a call to the service
        $retweeters = collect($this->twitterService->getRetweeters($tweet_id));
        $total_followers = $retweeters->sum('user.followers_count');
        
        //Cache tis request in the database till the next call;
        $this->storeTweet($tweet_id, $total_followers);
        
        return $total_followers;
    }
    
    /**
     * Checks if there is a tweet in the database with the tweet_id that hasnt
     * been updated in the specified period
     *
     * @param  string  $tweet_id
     * @return boolean 
     */
    private function hasCachedTweet($tweet_id) {
        $twoHoursAgo = \Carbon\Carbon::now()
                            ->subHours(static::CACHE_DURATION_HOURS)
                            ->toDateTimeString();
        
        return Tweet::where('tweet_id', $tweet_id)
                ->where('updated_at', '>=', $twoHoursAgo)
                ->first();
    }
    
    
    /**
     * Gets the tweet in the database with the specified tweet_id
     * 
     * @param  string  $tweet_id
     * @return bool 
     */
    private function getCachedTweet($tweet_id) {
        return 
                Tweet::where('tweet_id', $tweet_id)
                ->first();
    }
    
    /**
     * Stores/Updateds the follower count with a tweet id in the database
     * 
     * @param  string  $tweet_id
     * @return bool 
     */
    private function storeTweet($tweet_id, $total_followers) {
        
        $tweet = Tweet::firstOrNew([
                'tweet_id' => $tweet_id
            ]);
        $tweet->total_follower_count = $total_followers;
        
        $tweet->save();
    }
}