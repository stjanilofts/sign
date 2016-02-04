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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/sticky.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.15/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.js"></script>
        <script>
        Vue.config.debug = false;
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        </script>

        <link href='https://fonts.googleapis.com/css?family=Cabin:400,700' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="mainwrap">
            <div class="haus">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-3 uk-hidden-small">
                        &nbsp;
                    </div>
                    <div class="uk-width-medium-1-3 uk-text-center">
                        <a href="/" id="logo"><img src="/img/logo.png" /></a>
                    </div>
                    <div class="uk-width-medium-1-3 cart-widget-container uk-flex uk-flex-top uk-flex-right">
                        <a class="nohover" href="/hafa-samband/"><h3><i class="uk-icon-phone-square uk-margin-right"></i>511 2004</h3></a>
                    </div>
                    {{-- <div class="uk-width-medium-1-3 cart-widget-container uk-flex uk-flex-center uk-text-center uk-flex-middle">
                        @include('frontend.cart.widget', ['elementId' => 'cart-widget'])
                    </div> --}}
                </div>
            </div>


            <div class="menu normal">
                <nav class="top">
                    {!! kalMenuExpandedAll(['topmenu' => true, 'hidesmall' => true]) !!}
                    @include('frontend.cart.widget', ['elementId' => 'cart-widget-nav'])
                    <div>
                        <a href="#my-id" data-uk-offcanvas><i class="uk-icon-bars uk-margin-right"></i>Meira</a>
                    </div>
                </nav>
            </div>



            @if(frontpage())
                @if(isset($forsidumyndir) && !$forsidumyndir->isEmpty())
                    <div class="front">
                        <div class="uk-grid uk-grid-collapse">
                            <div class="uk-width-medium-3-5 uk-width-large-3-4">
                                <div class="forsidumyndir">
                                    <div class="uk-slidenav-position" data-uk-slideshow="{autoplay: true, autoplayInterval: 3000, animation: 'swipe'}">
                                        <ul class="uk-slideshow">
                                            @foreach($forsidumyndir as $key => $mynd)
                                                <li>
                                                    <div class="forsidumynd" style="background-image: url('/imagecache/banner/{{ $mynd->img()->first() }}');">
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if(count($forsidumyndir) > 0)
                                            <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
                                            <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="uk-width-medium-2-5 uk-width-large-1-4 uk-flex">
                                <div class="fullbar uk-flex uk-flex-middle uk-flex-center uk-flex-wrap uk-overflow-hidden">
                                    <h4 data-uk-scrollspy="{cls:'uk-animation-slide-bottom uk-animation-1', delay: 600}">Dún og fiður ehf framleiðir sjálf allar söluvörur sínar s.s. sængur, kodda, púða og pullur að undanskildum utanyfirverum og lökum.</h4>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        

            @if(frontpage())
                <div class="fullbar yellow uk-flex uk-flex-middle uk-flex-center uk-flex-wrap" style="background-image: url('/img/fjodur2.png'); background-position: 65% center; background-repeat: no-repeat; background-size: contain;">
                    <h2 data-uk-scrollspy="{cls:'uk-animation-slide-left uk-animation-1', delay: 600}">Starfrækt síðan 1959</h2>
                </div>
            @endif


            @if(frontpage())
                <div>
                    <div class="boxes uk-grid uk-grid-collapse">
                        <div class="uk-width-medium-1-3">
                            <div class="box uk-flex" style="background: url('/imagecache/boximg/myndBjarni05.jpg') center center no-repeat; background-size: cover;">
                                <a href="/fyrirtaekid/" class="box__centertext box--hover uk-flex uk-flex-center uk-flex-middle uk-flex-wrap">
                                    <h4>FYRIRTÆKIÐ</h4>
                                </a>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-3">
                            <div class="box uk-flex" style="background: url('/imagecache/boximg/12.jpg') center center no-repeat; background-size: cover;">
                                <a href="/vorur/" class="box__centertext box--hover uk-flex uk-flex-center uk-flex-middle uk-flex-wrap">
                                    <h4>VÖRUR</h4>
                                </a>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-3">
                            <div class="box uk-flex" style="background: url('/imagecache/boximg/image004.jpg') center center no-repeat; background-size: cover;">
                                <a href="/hreinsun/" class="box__centertext box--hover uk-flex uk-flex-center uk-flex-middle uk-flex-wrap">
                                    <h4>HREINSUN</h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            @if(frontpage())
                <div>
                    <div class="boxes uk-grid uk-grid-collapse">
                        <div class="uk-width-medium-2-5">
                            <div class="box uk-flex">
                                <div class="box__centertext bg-grey uk-flex uk-flex-center uk-flex-middle uk-flex-wrap uk-flex-column">
                                    <div>
                                        <h3><i class="uk-icon-clock-o uk-margin-right"></i>Opnunartímar</h3>
                                        <div class="opnunartimar">
                                            <div>
                                                Virkir dagar
                                                <span>09:00 - 18:00</span>
                                            </div>
                                            <div>
                                                Laugardagar
                                                <span>11:00 - 16:00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="uk-width-medium-3-5">
                            <div class="box uk-flex" style="background-image: url('/imagecache/original/bg-kort.jpg'); background-repeat: no-repeat; background-position: center center; background-size: cover;">
                                <a href="/fyrirtaekid/stadsetning/"
                                   class="box__centertext box--hover uk-flex uk-flex-center uk-flex-middle uk-flex-wrap uk-flex-column">
                                    <h3><i class="uk-icon-map-marker uk-margin-right"></i>Staðsetning okkar</h3>
                                    <div>
                                        Kíktu á kortið
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            @if(!frontpage())
                <div class="content-container" style="padding-bottom: 80px !important;">
                    <header style="background: url('/imagecache/header/{{ (isset($banner) ? $banner : '2.jpg') }}') center center no-repeat; background-size: cover;">
                        <h1>@yield('title')</h1>
                    </header>

                    <div class="content">
                        @yield('content')
                    </div>
                </div>
            @endif

            <div class="footer">
                <ul class="bottom-ul">
                    <li>&copy; Dún og fiður ehf</li>
                    <li>Kt. 670901 2540</li>
                    <li>Laugavegur 86, 101 Reykjavík</li>
                    <li><i class="uk-icon-phone-square uk-margin-right"></i>511 2004</li>
                    <li><i class="uk-icon-fax uk-margin-right"></i>511 2003</li>
                    <li><a href="mailto:dunogfidur@dunogfidur.is"><i class="uk-icon-envelope-o uk-margin-right"></i>dunogfidur@dunogfidur.is</a></li>
                    <li><a href="https://www.facebook.com/dunogfidur.is" class="fbcolor" title="Dún og fiður á Facebook"><i class="uk-icon-facebook-square uk-icon-small uk-margin-right"></i>Facebook</a></li>
                </ul>
            </div>

        </div>


        <div id="my-id" class="uk-offcanvas">
            <div class="uk-offcanvas-bar">
                <nav class="offcanvas">
                    {!! kalMenuExpandedAll() !!}
                </nav>
            </div>
        </div>

        <script src="/js/scripts.js?v=2"></script>
    </body>
</html>
