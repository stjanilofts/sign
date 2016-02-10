@extends('frontend.layout')

@section('title', isset($pagetitle) ? $pagetitle : '')

@section('content')

	<div class="products">
        <div class="uk-grid uk-grid-collapse">
            <div class="uk-width-medium-1-5">
                <div id="texti"></div>
                <ul class="grid-filter uk-subnav uk-subnav-pill">
                    <li data-group="kyn" data-filter=""><a><i class="uk-icon-genderless uk-margin-right"></i>Bæði</a></li>
                    <li data-group="kyn" data-filter="karlar"><a><i class="uk-icon-male uk-margin-right"></i>Karlar</a></li>
                    <li data-group="kyn" data-filter="konur"><a><i class="uk-icon-female uk-margin-right"></i>Konur</a></li>
                    @foreach(\App\Product::collections() as $k => $v)
                        <li data-group="collection" data-filter="{{ $k }}"><a>{{ $v }}</a></li>
                    @endforeach
                </ul>
                <script>
                $(document).ready(function() {
                    var groups = [];

                    $('.grid-filter a').click(function() {
                        var ctx = $(this).parents('li')

                        group = ctx.attr('data-group')
                        filter = ctx.attr('data-filter')

                        if(groups[group] && groups[group].trim() === filter.trim()) {
                            groups[group] = '';
                        } else {
                            groups[group] = filter.trim();
                        }

                        $('#texti').html('');
                        for(k in groups) {
                            $('#texti').append(k + ': ' + groups[k] + '<br>');
                        }

                        $.each($('.filtered-product'), function(i, v) {
                            var $el = $(v);

                            var matches = true;

                            for(var k in groups) {
                                var filters = $el.attr('data-filter-' + k).trim().split(' ')
                                
                                if(groups[k].trim() != '') {
                                    for(var f in filters) {
                                        var _filter = filters[f].trim();
                                        if(_filter != '') {
                                            if(groups[k] != _filter) {
                                                matches = false;
                                            }
                                        }
                                    }
                                }

                                if(!matches) {
                                    $el.hide();
                                } else {
                                    $el.show();
                                }
                            }
                        })
                    })
                })
                </script>
            </div>

            <div class="uk-width-medium-4-5">            
                <div class="Boxes">
                    <div class="uk-grid uk-grid-collapse uk-grid-match filtered-products" data-uk-grid-match="{target:'.Box'}">
                        @foreach($items as $item)   
                            <?php

                            $kyn = (isset($item->karlar) && $item->karlar > 0 ? 'karlar ' : '')
                                  .(isset($item->konur) && $item->konur > 0 ? 'konur ' : '');

                            ?>
                            <div class="filtered-product uk-width-large-1-3 uk-width-small-1-2"
                                 data-filter-collection="{{ isset($item->collection) && $item->collection ? $item->collection : '' }}"
                                 data-filter-kyn="{{ $kyn }}">
                                <div class="Box Box--alt" style="background-image: url('/imagecache/large/{{ $item->img()->first() }}')">
                                    {{ isset($item->collection) && $item->collection ? $item->collection : '' }}<br>
                                    {{ $kyn }}<br>

                                    <div class="Box--content">
                                        <div class="uk-flex uk-flex-center uk-flex-middle uk-height-1-1 uk-flex-column">
                                            <h2>{{ $item->title }}</h2>
                                            <a href="#" class="Takki">Skoða</a>
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