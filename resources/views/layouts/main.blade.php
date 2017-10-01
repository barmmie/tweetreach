<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TweetReach</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css" />
        <!-- Fonts -->
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                
                font-family: BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
               
                height: 100vh;
                margin: 0;
            }

            .container {
              text-align:center;
              width:100%;
              height:auto;
              padding-top:10vh;
              margin:0 auto;
            }
            
            .header {
                font-weight: 100;
                color: #636b6f;
                padding-bottom: 11vh;
            }
            
            h3.title {
                font-size: 84px;
            }
            
            h3.title a:hover {
                text-decoration:none;
                color: inherit;
            }
            
            #tweetContainer {
                margin: 0 auto;
                display: inline-block;
            }
            
            .result-block {
                background-color: #FAFAFA;
                border: 1px solid #EFEFEF;
                padding: 15px 15px 20px 15px;
                border-radius: 3px;
            }
            
            a.statcard i,.statcard h4.list-group-item-heading { color:#00acee; }
            a.statcard:hover { background-color:#00acee; }
            a.statcard:hover * { color:#FFF; }
            

     </style>
    </head>
    <body>
        
        <div class="container">
            <div class="header">
                <h3 class="title"><a href="{{route('home')}}"><i class="fa fa-twitter"></i> TweetReach</a></h3>
            </div>
            @yield('content')
                    
</div>         
               
                    
                    
          @yield('scripts')   
    </body>
</html>
