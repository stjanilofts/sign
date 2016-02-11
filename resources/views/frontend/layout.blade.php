<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>{{ config('formable.site_title') }}{{ isset($pagetitle) ? ' | '.$pagetitle : '' }}</title>

        <meta name="description" content="">
        <meta name="keywords" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="token" name="token" value="{{ csrf_token() }}">
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/uikit.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/slideshow.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/slider.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/dotnav.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/notify.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/slidenav.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/tooltip.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/sticky.min.css" rel="stylesheet">
        <link href="/css/app.css?v=2" rel='stylesheet' type='text/css'>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/uikit.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/slideshow.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/slider.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/slideset.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/notify.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/tooltip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/lightbox.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/grid.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/sticky.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.15/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.js"></script>

        <script>
        Vue.config.debug = true;
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        </script>

        <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="Wrapper">
            <div class="Haus {{ ( ! \Request::is('/') ? 'Haus--subpage' : '' ) }}">
                <nav class="top" data-uk-sticky="{clsactive: 'sticky-active', clsinactive: 'sticky-inactive', top: {{ ( \Request::is('/') ? '-600' : '-300' ) }}, clsinit: 'sticky-init', animation: 'uk-animation-slide-top' }">
                    <?php

                    $menu = kalTopMenu();
                    $menuCount = count($menu);

                    ?>
                    @foreach(kalTopMenu() as $k => $menuItem)
                        @if(ceil($menuCount / 2) == $k)
                            <div>
                                <a href="/" id="logo"><img src="/img/logo.png" /></a>
                            </div>
                        @endif
                        
                        <div class="{{ $menuItem['hidden_small'] ? 'hidden-small' : '' }}">
                            <a href="{{ $menuItem['path'] }}">{!! $menuItem['title'] !!}</a>
                        </div>
                    @endforeach

                    <div class="hidden-small">
                        <a href="/karfa/"><i class="uk-icon-shopping-cart uk-margin-right"></i>Karfa</a>
                    </div>
                </nav>
            </div>

            @if(frontpage())
                <div class="Front">
                    <ul class="uk-slideshow" data-uk-slideshow="{autoplay:true}">
                        <li style="background-image: url('/img/big.jpg');" width="" height="" alt=""></li>
                    </ul>
                </div>

                <?php

                $myndir = [
                    'Eldur og ís' => 'eldur-og-is.jpg',
                    'Dulúð' => 'mystic.jpg',
                    'Straumur' => 'straumur.jpg',
                    'Piece of Iceland' => 'piece-of-iceland.jpg',
                    'Rock' => 'rock.jpg',
                    'Signs by sign' => 'signs-by-sign.jpg',
                ];

                ?>
                <div class="Boxes">
                    <div class="uk-grid uk-grid-collapse uk-grid-match" data-uk-grid-match="{target:'.Box'}">
                        @foreach($myndir as $name => $mynd)
                            <div class="uk-width-large-1-3 uk-width-small-1-2">
                                <div class="Box Box--shaded" style="background-image: url('/imagecache/large/{{ $mynd }}')">
                                    <div class="Box--content">
                                        <div class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1 uk-flex-column">
                                            <h2>{{ $name }}</h2>
                                            <a href="#" class="fancy-takki">Skoða</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!frontpage())
                <div class="Content">
                    <header>
                        <h1>@yield('title', 'bla')</h1>
                    </header>

                    <div>
                        @yield('content')
                    </div>
                </div>
            @endif

            <div class="Footer">
            </div>
        </div>

        <script src="/js/scripts.js?v=2"></script>
    </body>
</html>
