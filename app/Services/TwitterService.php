<?php

namespace App\Services;

use Config;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class TwitterService {
    
    /**
     * The http client for making calls instance.
     *
     * @var \GuzzleHttp\Client
     */
    private $client;
    
    /**
     * The config facade instance.
     *
     * @var \Config
     */
    private $config;
    
    /**
     * The base api url.
     *
     * @var string
     */
    const BASE_API_URL = 'https://api.twitter.com/1.1/';
    
    /**
     * Create a new Twitter Service instance.
     *
     * @param  \GuzzleHttp\Client $client
     * @param  \Config $config
     * @return void
     */
    public function __construct( Client $client, Config $config) {

        $this->client = $client;
        $this->config = app('config');
    }
    
    /**
     * Creates an oauth middleware for twitter
     *
     * @return  \GuzzleHttp\Subscriber\Oauth\Oauth1
     */
    private function createOauthMiddleware() {
        $middleware = new Oauth1([
            'consumer_key'  => $this->config->get('services.twitter.consumer_key'),
            'consumer_secret' => $this->config->get('services.twitter.consumer_secret'),
            'token'       => $this->config->get('services.twitter.access_token'),
            'token_secret'  => $this->config->get('services.twitter.access_token_secret')
        ]);
        
        return $middleware;

    }
    
    /**
     * Gets the retweet for the specified  tweet_id
     *
     * @return  mixed
     */
    public function getRetweeters($tweet_id) {
        
        $url = "statuses/retweets/$tweet_id.json";
        $params = ['query' => [
            'count' => '100',
        ]];
        $res = $this->request($url, $params);

        $retweeters = json_decode($res->getBody());
        return $retweeters;
        
    }
    
    /**
     * Makes an authenticated call to a twitter endpoint
     *
     * @return  
     */
    private function request($url, $params) {
        $stack = HandlerStack::create();
        $middleware = $this->createOauthMiddleware();
        $stack->push($middleware);

        $client = new Client([
            'base_uri' => static::BASE_API_URL,
            'handler' => $stack,
            'auth' => 'oauth',
        ]);

        $res = $client->get($url, $params);
        return $res;
    }

    
    
    
}