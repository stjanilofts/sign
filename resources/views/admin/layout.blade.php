<!doctype html>
<html class="no-js" lang="is">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{ config('formable.site_title') }} stjórnborð</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="token" name="token" value="{{ csrf_token() }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/uikit.almost-flat.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/sortable.almost-flat.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/datepicker.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/notify.almost-flat.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/slidenav.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/css/components/dotnav.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.css">
        
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/uikit.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/slideset.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/slider.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/sortable.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/datepicker.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.24.3/js/components/notify.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.15/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.6.1/vue-resource.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.js"></script>

        <script src="/ckeditor/ckeditor.js"></script>

        <script>
        CKEDITOR.config.height = 400;
        Vue.config.debug = true;
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        </script>

        <style>
            html {
                font-family: 'Roboto', sans-serif !important;
                background: #333;
            }
            h1, h2, h3, h4, h5, h6, p, em, strong {
                font-family: 'Roboto', sans-serif !important;
            }
            body {
                font-family: 'Roboto', sans-serif !important;
            }
        </style>

        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,100italic,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    </head>
    <body style="padding-bottom: 60px;">
        <div class="uk-container uk-container-center uk-margin-large-top" style="background: #FFF; box-shadow: 0px 0px 8px rgba(0,0,0,0.1); border-radius: 4px;">
       		<h2 class="uk-margin-top"><strong>{{ config('formable.site_title') }} stjórnborð.</strong></h2>

    		<nav class="uk-navbar">
			    <ul class="uk-navbar-nav">
                    <li class="{{ (Request::is('admin') ? 'uk-active' : '') }}"><a href="/admin">Heim</a></li>
                    @foreach(config('formable.hlutir') as $hlutur)
                        <?php

                        $class = "\App\\".ucfirst($hlutur);
                        $model = new $class;
                        $heiti = $model->pluralName() ?: ucfirst($hlutur);

                        ?>
                        <li class="{{ (Request::is('admin/'.strtolower(trim($hlutur)).'*') ? 'uk-active' : '') }}"><a href="/admin/{{ strtolower(trim($hlutur)) }}">{{ $heiti }}</a></li>
                    @endforeach

                    <li class="{{ (Request::is('admin/orders*') ? 'uk-active' : '') }}"><a href="/admin/orders/">Pantanir</a></li>
			    </ul>

                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav">
                        <li><a href="/auth/logout">Skrá út</a></li>
                    </ul>
                </div>
			</nav>

        	<div class="uk-margin-top uk-margin-large-bottom">

                @if(\Request::is('admin'))
                    <h3>Velkomin(n)</h3>
                    <p>Þú ert innskráð(ur) sem "<em>{{ \Auth::user()->name }}</em>".</h3>

                    {{-- <form class="uk-form uk-form-stacked">
                        <div class="uk-form-row">
                            <label class="uk-form-label">Netfang</label>
                            <div class="uk-form-controls">
                                <input type="email" name="company_email" value="{{ config('formable.company_email') }}"/>
                            </div>
                        </div>

                        <div class="uk-form-row">
                            <label class="uk-form-label">Nafn fyrirtækis</label>
                            <div class="uk-form-controls">
                                <input type="text" name="company_name" value="{{ config('formable.company_name') }}" />
                            </div>
                        </div>
                    </form> --}}                 
                @endif

        		@yield('content')

        	</div>

            <hr>

            <p class="uk-text-center">Netvistun &copy;</p>
        </div>

        <script>
        </script>
    </body>
</html>