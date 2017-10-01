
    @extends('layouts.main')
    
    
    @section('content')
    
    
        <div class="row">
            <form action="{{route('tweets.store')}}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 col-md-6 col-offset-3">
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{session()->get('error')}}
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="input-group input-group-lg {{$errors->has('url') ? 'has-error': ''}}">
                              
                      <input type="text" name="url" value="{{old('url')}}" class="form-control" placeholder="Enter the url for a tweet...">
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go!</button>
                      </span>
                    </div>
                    @if($errors->has('url'))
                         <label class="help-block text-danger" for="cc_num">{{$errors->first('url')}}</label>
                     @endif
                    </div>
                    
                    
                    
                <div>
            </form>
            
        </div>

    
    @endsection
    
