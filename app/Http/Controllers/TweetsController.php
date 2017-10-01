<?php

namespace App\Http\Controllers;

use App\Services\TweetCacheService;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    /**
     * Index page where users enters for a url.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('tweets.index');
    }
    
    
     /**
     * Validates the url elntered by the user. 
     * 
     * Extracts valid tweetId from url and redirect to appropriate handler
     * Redirects back if not valid.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $twitter_regex = '/http(?:s)?:\/\/(?:www.|mobile.|m.)?twitter\.com\/([a-zA-Z0-9_]+)\/status\/([0-9]+)/';
        
        $request->validate([
            'url' => ['required', 'url', "regex:$twitter_regex"],
        ]);
            
        $match_found = preg_match($twitter_regex, $request->get('url'), $matches); 
        
        if(!$match_found || !isset($matches[2])) {
            session()->flash('error', 'We could not find a valid twitter id in the format selected');
            return redirect()->back();
        }
        
        $tweet_id = $matches[2];
        $request->flashOnly(['url']);
        return redirect()->route('tweets.show', $tweet_id);    
    }
    
    /**
     * Gets and displays the follower count from the cache service
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\TweetCacheService  $tweetCacheService
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $tweet_id, TweetCacheService $tweetCacheService) {
        try {
            $total_followers = $tweetCacheService->getTweet($tweet_id);

        } catch(\Exception $e) {
            session()->flash('error', "An error occured while fetching your response: {$e->getMessage()}");
            //Keep the url entered by the use so we can show it to him again
            $request->session()->keep(['url']);

            return redirect()->route('home');
            
        }
        
        return view('tweets.show', compact('total_followers', 'tweet_id'));
    }
}
