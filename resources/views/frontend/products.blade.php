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

                    <div class="filteritem uk-visible-large">
                        <h5 class="uk-text-center">Kyn</h5>
                        <ul class="grid-filter">
                            <li data-group="kyn" data-filter=""><a class="default filter-active"><i class="uk-icon-genderless uk-margin-right"></i>Öll kyn</a></li>
                            <li data-group="kyn" data-filter="karlar"><a><i class="uk-icon-male uk-margin-right"></i>Karlar</a></li>
                            <li data-group="kyn" data-filter="konur"><a><i class="uk-icon-female uk-margin-right"></i>Konur</a></li>
                        </ul>
                    </div>

                    <div class="filteritem uk-visible-large">
                        <h5 class="uk-text-center">Lína</h5>
                        <ul class="grid-filter">
                            <li data-group="collection" style="" data-filter=""><a class="default filter-active">Allar línur</a></li>
                            @foreach($collections as $k => $v)
                                <li data-group="collection" style="background-color: {{ $v['color'] }};" data-filter="{{ $k }}"><a>{{ $v['title'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <script>
                $(document).ready(function() {
                    /*$('[data-uk-grid]').on('beforeupdate.uk.grid', function(e, children) {
                        console.log(children);
                    });*/

                    var groups = [];

                    $('.grid-filter a').click(function() {
                        $('.filtered-product').hide();

                        $anchor = $(this);

                        var ctx = $(this).parents('li')

                        group = ctx.attr('data-group')
                        filter = ctx.attr('data-filter')

                        if(groups[group] && groups[group].trim() === filter.trim()) {
                            //groups[group] = '';
                            //$anchor.removeClass('filter-active');
                        } else {
                            groups[group] = filter.trim();
                            $('.grid-filter a').removeClass('filter-active');
                            $anchor.addClass('filter-active');
                        }

                        if( ! ($('.grid-filter a').hasClass('filter-active'))) {
                            $('.grid-filter a.default').addClass('filter-active');
                        }

                        /*$('#texti').html('');

                        for(k in groups) {
                            $('#texti').append(k + ': ' + groups[k] + '<br>');
                        }*/

                        $.each($('.filtered-product'), function(i, v) {
                            var $el = $(v);

                            var matchedElements = [];

                            var counter = 0;
                            var requiredMatches = 0;
                            var foundMatches = 0;

                            for(var k in groups) {
                                var filters = $el.attr('data-filter-' + k).trim().split(' ')
                                
                                if(filters.length && groups[k].trim() != '') {
                                    requiredMatches++;

                                    for(var f in filters) {
                                        var _filter = filters[f].trim();
                                        
                                        if(_filter != '') {
                                            if(groups[k] == _filter) {
                                                foundMatches++;
                                            }
                                        }
                                    }
                                }
                            }

                            if(requiredMatches == foundMatches) {
                                $el.show();
                                //UIkit.$html.trigger('changed.uk.dom');
                            }
                        })
                    })
                })
                </script>
            </div>

            <div class="uk-width-large-4-5">
                <div class="Boxes" style="padding: 10px;">
                    <div class="uk-grid uk-grid-small uk-grid-match filtered-products" data-uk-grid-match="{target:'.Product'}">                    
                        @foreach($items as $item)   
                            <?php

                            $path = $item->fullPath();
                            $kyn = (isset($item->karlar) && $item->karlar > 0 ? 'karlar ' : '')
                                  .(isset($item->konur) && $item->konur > 0 ? 'konur ' : '');

                            ?>
                            <div class="filtered-product uk-width-large-1-4 uk-width-medium-1-3 uk-width-small-1-2"
                                 data-filter-collection="{{ isset($item->collection) && $item->collection ? $item->collection : '' }}"
                                 data-filter-kyn="{{ $kyn }}">
                                <div class="Product">
                                    <div class="Badge Badge--kyn">
                                        @if((isset($item->konur) && $item->konur > 0))
                                            <span data-uk-tooltip title="Konur"><i class="uk-icon-female"></i></span>
                                        @endif
                                        @if((isset($item->karlar) && $item->karlar > 0))
                                            <span data-uk-tooltip title="Karlar"><i class="uk-icon-male"></i></span>
                                        @endif

                                        @if(($item->modelName() !== 'Category') && !$item->konur && !$item->karlar)
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
                                       href="{{ $path }}"
                                       style="background: #FFF url('/imagecache/product/{{ isset($image) ? $image : $item->img()->first() }}') center center no-repeat;
                                            background-size: {{ isset($fillimage) && $fillimage ? 'cover' : 'contain' }};">
                                    </a>

                                    <div class="Product--content uk-margin-top uk-margin-large-bottom">
                                        <div class="title uk-margin-small-bottom">
                                            <h5><a href="{{ $path }}">{{ $item->title }}</a></h5>
                                        </div>
                                        <div class="price">
                                            {{ $item->priceFormatted }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
            isProcessing: false
        },

        ready: function() { 
        },

        methods: {
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