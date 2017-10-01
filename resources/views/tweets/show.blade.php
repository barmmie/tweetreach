@extends('layouts.main')

@section('content')

<div class="row">
    <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2">
        <div class="alert alert-info">
            Showing results for tweet <strong>{{$tweet_id}}</strong>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8  col-sm-offset-2 col-sm-12">
            <div id="tweetContainer">
                
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-sm-12">
            <div class="result-block">
					<h4>Total reach</h4>
					<h2 class="bold padding-bottom-7">{{number_format($total_followers)}}</h2>
					<p>Sum total of followers of people who retweeted this tweet</p>
				</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    
    window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);

  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };

  return t;
}(document, "script", "twitter-wjs"));

twttr.ready(function(){
    twttr.widgets.createTweet(
      '{{$tweet_id}}',
      document.getElementById('tweetContainer'),
      {
        
      }
);
})

</script>
@endsection