@extends('frontend.layout')

@section('title', isset($pagetitle) ? $pagetitle : '')

@section('content')

    <?php

    $collections = \App\Product::collections();

    ?>

	<div class="products">
        <div class="uk-grid uk-grid-collapse">
            <div class="uk-width-large-1-5">
                <!--<div id="texti"></div>-->
                <!--<div data-uk-sticky="{top: 120, boundary: true}">-->
                <div>
                    <div class="filteritem">
                        <nav class="leftmenu">
                            <h5 class="uk-text-center">Flokkar</h5>
                            {!! kalCategoryMenu() !!}
                        </nav>
                    </div>

                    <div id="filters">
                        <div class="filteritem uk-visible-large">
                            <h5 class="uk-text-center">Kyn</h5>
                            <ul class="grid-filter">
                                <li data-group="kyn" data-filter=""><a @click.prevent="addFilter" class="default filter-active"><i class="uk-icon-genderless uk-margin-right"></i>Öll kyn</a></li>
                                <li data-group="kyn" data-filter="karlar"><a @click.prevent="addFilter"><i class="uk-icon-male uk-margin-right"></i>Herrar</a></li>
                                <li data-group="kyn" data-filter="konur"><a @click.prevent="addFilter"><i class="uk-icon-female uk-margin-right"></i>Dömur</a></li>
                            </ul>
                        </div>

                        <div class="filteritem uk-visible-large">
                            <h5 class="uk-text-center">Vörulína</h5>
                            <ul class="grid-filter">
                                <li data-group="collection" style="" data-filter=""><a @click.prevent="addFilter" class="default filter-active">Allar vörulínur</a></li>
                                @foreach($collections as $k => $v)
                                    <li data-group="collection" style="background-color: {{ $v['color'] }};" data-filter="{{ $k }}"><a @click.prevent="addFilter">{{ $v['title'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        
                        
                        {{--<pre>

                        @{{ filterCount | json 4 }}

                        @{{ filters | json 4 }}

                        </pre>--}}
                        

                        
                    </div>
                </div>
            </div>

            <div class="uk-width-large-4-5">
                <div class="Boxes" style="padding: 10px;">
                    @if( ! $items->isEmpty() )
                        <?php

                        $productFound = false;

                        ?>
                        @foreach($items as $item)
                            <?php

                            $productFound = ($item->modelName() == 'Product');

                            ?>
                        @endforeach

                        @if($productFound)
                            <div v-cloak v-if="matchedFilters < 1" class="uk-text-large uk-text-bold uk-margin-bottom">
                                <div><i class="uk-icon-question-circle uk-margin-right"></i>Ekkert fannst með þessum síum! <a @click="clearFilters">Smelltu hér</a> til að taka burt síur.</div>
                            </div>
                        @endif
                    @endif

                    <div class="uk-grid uk-grid-small uk-grid-match filtered-products" data-uk-grid-match="{target:'.Product'}">
                        @forelse($items as $item)   
                            <?php

                            $isCategory = ($item->modelName() == 'Category');

                            $path = $item->fullPath();
                            $kyn = (isset($item->karlar) && $item->karlar > 0 ? 'karlar ' : '')
                                  .(isset($item->konur) && $item->konur > 0 ? 'konur ' : '');

                            ?>
                            <div class="filtered-product {{ $isCategory ? 'category' : 'product' }} uk-width-large-1-4 uk-width-medium-1-3 uk-width-small-1-2"
                                 data-filter-collection="{{ isset($item->collection) && $item->collection ? $item->collection : '' }}"
                                 data-filter-kyn="{{ $kyn }}">
                                <div class="Product">
                                    {{ $item->product_type }}
                                    <div class="Badge Badge--kyn">
                                        @if((isset($item->konur) && $item->konur > 0))
                                            <span data-uk-tooltip title="Dömur"><i class="uk-icon-female"></i></span>
                                        @endif
                                        @if((isset($item->karlar) && $item->karlar > 0))
                                            <span data-uk-tooltip title="Herrar"><i class="uk-icon-male"></i></span>
                                        @endif

                                        @if(!$isCategory && !$item->konur && !$item->karlar)
                                            <span data-uk-tooltip title="Öll kyn"><i class="uk-icon-genderless"></i></span>
                                        @endif
                                    </div>

                                    @if(isset($item->collection) && $item->collection)
                                        <div class="Badge Badge--collection"
                                             data-uk-tooltip title="{{ $collections[$item->collection]['title'] }}"
                                             style="background-color: {{ $collections[$item->collection]['color'] }};">
                                        </div>
                                    @endif

                                    <a class="Product--image"
                                       href="/vefverslun/{{ $path }}"
                                       style="background: #FFF url('/imagecache/product/{{ isset($image) ? $image : $item->img()->first() }}') center center no-repeat;
                                            background-size: {{ isset($fillimage) && $fillimage ? 'cover' : 'contain' }};">
                                    </a>

                                    <div class="Product--content uk-margin-top uk-margin-large-bottom">
                                        <div class="title uk-margin-small-bottom">
                                            <h5><a href="/vefverslun/{{ $path }}">{{ $item->title }}</a></h5>
                                        </div>
                                        <div class="price">
                                            {{ $item->priceFormatted }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h3>Engar vörur að finna hér!</h3>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!--<pre>
        @{{ item | json 4 }}
        </pre>-->

	</div>

    <script>
    var product = new Vue({
        el: '.products',

        data: {
            added: false,
            isProcessing: false,

            filters: {
                collection: '',
                kyn: ''
            },

            matchedFilters: 1
        },

        ready: function() { 
            this.readCookies();
            this.setClasses();
        },

        computed: {
            filterCount: function() {
                var count = 0;

                for(var i in this.filters) {
                    if(this.filters[i] != '') count++;
                }

                return count;
            }
        },

        methods: {
            clearFilters: function() {
                for(v in this.filters) {
                    this.filters[v] = '';
                }

                this.matchedFilters = 1;

                $('.grid-filter').find('li > a').removeClass('filter-active');
                $('.grid-filter').find('li > a.default').addClass('filter-active');

                this.reFilter();
            },

            readCookies: function() {
                function getCookie(name) {
                    var value = "; " + document.cookie;
                    var parts = value.split("; " + name + "=");
                    if (parts.length == 2) return parts.pop().split(";").shift();
                }

                for(v in this.filters) {
                    var c = getCookie(v);

                    if(c) {
                        this.filters[v] = c;
                    }
                }

                this.reFilter();
            },

            setCookies: function() {
                for(v in this.filters) {
                    document.cookie = v + '=' + this.filters[v] + ';path=/;expires=Fri, 31 Dec 9999 23:59:59 GMT;';
                }
            },

            addFilter: function(event) {
                $el = $(event.target)

                var ctx = $el.parents('li')

                var group = ctx.attr('data-group')
                var filter = ctx.attr('data-filter')

                ctx.siblings().find('a').removeClass('filter-active');
                ctx.find('a').addClass('filter-active');

                this.filters[group] = filter;

                this.reFilter();
            },

            setClasses: function() {
                $('.grid-filter').find('li > a').removeClass('filter-active');

                for(v in this.filters) {
                    if(this.filters[v] == '') {
                        $('.grid-filter').find('li[data-group=' + v + ']').find('a.default').addClass('filter-active');
                    } else {
                        $('.grid-filter').find('li[data-group=' + v + '][data-filter=' + this.filters[v] + ']').find('a').addClass('filter-active');
                    }
                }
            },

            matchFilter: function($elem) {
                var found = 0

                for(var f in this.filters) {
                    if(this.filters[f] != '') {
                        var filters = $elem.attr('data-filter-' + f)
                        
                        var filterArray = filters.split(' ');

                        for(var v in filterArray) {
                            var _filter = filterArray[v].trim();

                            if(_filter != '') {
                                if(_filter == this.filters[f]) {
                                    found++;
                                }
                            }
                        }
                    }
                }

                return this.filterCount == found
            },

            reFilter: function() {
                this.matchedFilters = 0

                $.each($('.filtered-product.product'), function(i, v) {
                    if(this.matchFilter($(v))) {
                        this.matchedFilters++;
                        $(v).show()
                    } else {
                        $(v).hide()
                    }
                }.bind(this))

                this.setCookies();
            },

            incrQty: function(event) {
                var $el = $(event.target);
                var $inp = $el.parents('[data-product-id]').find('input');
                var $inpVal = parseInt($inp.val(), 10);
                if($inpVal < 999) {
                    var $newInpVal = parseInt($inpVal) + 1;
                    $inp.val($newInpVal);
                }
            },

            decrQty: function(event) {
                var $el = $(event.target);
                var $inp = $el.parents('[data-product-id]').find('input');
                var $inpVal = parseInt($inp.val(), 10);
                if($inpVal > 1) {
                    var $newInpVal = parseInt($inpVal) - 1;
                    $inp.val($newInpVal);
                }
            },

            addToCart: function(event) {
                var $el = $(event.target);

                $el.attr('disabled', 'disabled');
                
                var default_text = $el.html();
                $el.html('Augnablik...<i class="uk-icon-spin uk-icon-spinner uk-margin-left"></i>');

                var $parent = $el.parents('[data-product-id]')

                var product_id = $parent.attr('data-product-id')
                var quantity = parseInt($parent.find('input').val(), 10);

                if(product_id && quantity >= 1) {                   
                    this.isProcessing = true;
                    this.$http.post('/_vorur/add-to-cart', { quantity: quantity, product_id: product_id }).then(function(response) {
                        this.isProcessing = false;
                        if(response.data.status == 'success') {
                            cart_widget.increment(quantity);
                            this.added = true;
                            
                            $el.removeAttr('disabled');
                            $el.html(default_text);

                            UIkit.notify("<span class='uk-text-center'><i class='uk-icon-small uk-icon-check-circle uk-margin-right'></i>Vöru var bætt í <a href='/karfa/'>körfu</a>!</span>", { pos: 'top-center' })
                        }
                    }.bind(this), function(response) {
                        $el.removeAttr('disabled');
                        $el.html('Villa!<i class="uk-icon-signal uk-margin-left"></i>');
                    });
                } else {
                    $el.removeAttr('disabled');
                    $el.html(default_text);
                }

                this.isProcessing = false;
            }
        }
    });
    </script>

@stop