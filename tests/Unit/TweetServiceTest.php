<?php

namespace Tests\Unit;
use Mockery;
use App\Services\TweetCacheService;
use App\Services\TwitterService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetServiceTest extends TestCase
{
    
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTweetsWithoutCacheCallApi()
    {
        $retweeters = [
            ['user' => ['followers_count' => 1200]], 
            ['user' => ['followers_count' => 400]], 
            ['user' => ['followers_count' => 400]]
            ];
        $partialResponse = json_decode(json_encode($retweeters, JSON_FORCE_OBJECT));
        
        
        $tweet_id = '882624263907409922';
        
        $service = Mockery::mock(TwitterService::class);
       
        $service->shouldReceive('getRetweeters')
                    ->once()
                    ->with($tweet_id)
                    ->andReturn($partialResponse);
                    
        $cacheService = new TweetCacheservice($service);
        
        $count = $cacheService->getTweet($tweet_id);
        
        $this->assertEquals($count, 2000);
        
    }
    
    public function testTweetsWithExistingCacheDoesntCallApi()
    {
        $service = Mockery::mock(TwitterService::class);
        $tweet_id = '882624263907409922';
        $tweet = factory(\App\Tweet::class)->create([
            'tweet_id' => $tweet_id,
        ]);
        $service->shouldReceive('getRetweeters')
                    ->never();
                    
        $cacheService = new TweetCacheservice($service);
        
        $count = $cacheService->getTweet($tweet_id);
        
        $this->assertEquals($count,$tweet->total_follower_count );
        
    }
    
    public function testTweetsWithExpiredCacheCallApi()
    {
        $service = Mockery::mock(TwitterService::class);
        
        $retweeters = [
            ['user' => ['followers_count' => 1200]], 
            ['user' => ['followers_count' => 1200]], 
            ['user' => ['followers_count' => 1600]]
            ];
        $partialResponse = json_decode(json_encode($retweeters, JSON_FORCE_OBJECT));;
        
        $tweet_id = '882624263907409922';
        $tweet = factory(\App\Tweet::class)->create([
            'tweet_id' => $tweet_id,
            //Simulates an expired tweet by increasing the updated time by 2 hours 2 minutes minutes
            'updated_at' => \Carbon\Carbon::now()->subHours(TweetCacheservice::CACHE_DURATION_HOURS)->subMinutes(2)
        ]);
       
        $service->shouldReceive('getRetweeters')
                    ->once()
                    ->with($tweet_id)
                    ->andReturn($partialResponse);
                    
        $cacheService = new TweetCacheservice($service);
        
        $count = $cacheService->getTweet($tweet_id);
        
        $this->assertEquals($count, 4000);
        $this->assertNotEquals($count,$tweet->total_follower_count );
        //Ensure that the new result is stored in the database
        $this->assertDatabaseHas('tweets', [
            'tweet_id' => $tweet_id,
            'total_follower_count' => 4000
            ]);
        
    }
}
