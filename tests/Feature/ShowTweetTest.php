<?php

namespace Tests\Feature;

use Mockery;
use App\Services\TweetCacheService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTweetTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    
    public function testThatValidTweetsHaveTweetIdAndFollowerCountReturned()
    {
        
        $service = Mockery::mock(TweetCacheService::class);
       
        $service->shouldReceive('getTweet')
                    ->once()
                    ->with('882624263907409922')
                    ->andReturn('2000');
                    
        $this->app->instance(TweetCacheService::class, $service);
                    
         $response = $this->get('tweets/882624263907409922');
         $response->assertStatus(200);
         $response->assertViewIs('tweets.show');
         
         $response->assertViewHas('total_followers', '2000');
         $response->assertViewHas('tweet_id', '882624263907409922');

    }
    
    public function testThatValidTweetsGetRedirected() {
         $validUrls = [
            'https://twitter.com/recruitz_io/status/882624263907409922',
            'https://mobile.twitter.com/recruitz_io/status/882624263907409922',
            'https://m.twitter.com/recruitz_io/status/882624263907409922',
            'https://www.twitter.com/recruitz_io/status/882624263907409922',
            
        ];
        
        foreach ($validUrls as $url) {
            $response = $this->post('/tweets', [
                'url' => $url
                ]);
                
            $response->assertRedirect('tweets/882624263907409922');

        }
    }
    
    public function testThatInvalidTweetsHaveError()
    {
        
       
        $invalidUrls = [
            'https://laravel.com/docs/5.5/container',
            'https://www.facebook.com/Recruitz.io/photos/a.460558317440429.1073741828.458779130951681/832466593582931/?type=3&theater',
            'https://recruitz.io/faq'
        ];
        
        
        foreach ($invalidUrls as $url) {
             $response = $this->post('/tweets', [
                'url' => $url
                ]);
           $response->assertSessionHasErrors(['url']); 
            $response->assertRedirect('/');
            
        }
        
        
    }
    
    
}
