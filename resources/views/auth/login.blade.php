@extends('frontend.layout')

@section('content')

    <div class="page">
        <form class="Basic" method="POST" action="{{ url('/auth/login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <input class="" type="email" name="email" placeholder="Netfang">
                    @if($errors->first('email'))
                        <small class="error">Netfang vantar eða ekki rétt stimplað inn.</small>
                    @endif
                </div>

                <div class="uk-width-medium-1-1">
                    <input class="" type="password" name="password" placeholder="Lykilorð">
                    @if($errors->first('password'))
                        <small class="error">Lykilorð vantar eða ekki rétt.</small>
                    @endif
                </div>

                <div class="uk-width-medium-1-1">
                    <button class="takki" href="#">Innskrá</button>
                </div>
            </div>
        </form>
    </div>
            

@stop

