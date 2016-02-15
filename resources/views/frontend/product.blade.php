@extends('frontend.layout')

@section('title', $item->title)

@section('content')

	<div class="product" id="product-view" v-cloak>

        <div class="uk-grid uk-grid-collapse">
            <div class="uk-width-large-1-5">
	            <!--<div data-uk-sticky="{top: 120, boundary: true}">-->
	            <div>
					<div class="filteritem">
	                    <nav class="leftmenu">
	                        <h5 class="uk-text-center">Flokkar</h5>
	                        {!! kalCategoryMenu() !!}
	                    </nav>
	                </div>
	            </div>
            </div>

            <div class="uk-width-large-4-5">
            	<div style="padding: 10px;">
					<div class="uk-grid uk-grid-medium" data-uk-grid-margin data-uk-grid-match>
						<div class="uk-width-medium-1-2">
							<div class="uk-grid uk-grid-small" data-uk-grid-margin>
								@if(isset($image))
									<div class="uk-width-1-1">
										<div class="product__mynd">
											<a title="{{ $image['title'] != $image['name'] ? $image['title'] : '' }}" href="/imagecache/original/{{ $image['name'] }}" data-uk-lightbox="{group:'myndir'}" /><img src="/imagecache/product/{{ $image['name'] }}" /></a>
										</div>
									</div>
								@endif


								@if(count($images) > 1)
									<div class="uk-width-1-1">
										<h3 class="uk-text-center-small">Myndir</h3>

										<div class="uk-margin" data-uk-slideset="{small: 3, medium: 5, large: 6}">
			                                <div class="uk-slidenav-position uk-margin">
			                                    <ul class="uk-slideset uk-grid uk-flex-center uk-grid-width-1-1 uk-grid-width-large-1-6 uk-grid-width-medium-1-4 uk-grid-width-small-1-2">
										        	@foreach($images as $k => $img)
										            	<li>
										            		<span class="product__aukamynd">
										            			<a title="{{ $img['title'] != $img['name'] ? $img['title'] : '' }}" href="/imagecache/original/{{ $img['name'] }}" data-uk-lightbox="{group:'aukamyndir'}"><img src="/imagecache/small/{{ $img['name'] }}" /></a>
										            		</span>
										            	</li>
										            @endforeach
			                                    </ul>
			                                    <!--<a href="#" class="uk-slidenav uk-slidenav-previous" data-uk-slideset-item="previous"></a>
			                                    <a href="#" class="uk-slidenav uk-slidenav-next" data-uk-slideset-item="next"></a>-->
		                                	</div>
		                                	<ul class="uk-slideset-nav uk-dotnav uk-flex-center">
		                                		<li data-uk-slideset-item="0" class="uk-active"><a></a></li>
		                                		<li data-uk-slideset-item="1" class=""><a></a></li>
		                                	</ul>
		                            	</div>
									</div>
								@endif

							</div>
						</div>

						<div class="uk-width-medium-1-2">
							<div class="product__info">
								<h3 class="uk-text-center-small">Lýsing</h3>
								{!! $item->content !!}
							</div>


							@if($item->tech)
							<div class="uk-margin-top">
								<table class="table-tech">
									@foreach(parseList($item->tech) as $k => $v)
										<tr>
											<td>{{ $k }}</td>
											<td>{{ $v }}</td>
										</tr>
									@endforeach
								</table>
							</div>
							@endif

							<hr>

							<div class="product__options uk-margin-top">
								<h3 class="uk-text-center-small">Valmöguleikar</h3>
								<div class="uk-grid uk-grid-small">
									<div v-for="option in options" class="product__option uk-width-1-2">
										<div v-if="option.type == 'select'">
											<label>@{{ option.text }}</label>
											<select v-model="selected[$index]" class="uk-width-1-1">
												<option v-for="value in option.values" :selected="$index == 0" :value="{ option_id: $parent.$index, value_id: $index, value: value, type: option.text }">
													@{{ value.text }}
												</option>
											</select>
										</div>
										<div v-if="option.type == 'radio'">
											<label>@{{ option.text }}</label>
											<span v-for="value in option.values">
											<input v-model="selected[$index]" type="radio"
												   :checked="$index == 0"
												   :value="{ option_id: $parent.$index, value_id: $index, value: value, type: option.text }">
													@{{ value.text }}
											</span>
										</div>
									</div>
								</div>

								<div class="product__option">
									<label>Magn</label>
									<input v-model="quantity | qtyFilter quantity" type="number" min="1" class="quantity_input" style="width: 50px;" />
								</div>

								<div class="uk-margin-top">
									<div class="product__price">Verð @{{ priceFormatted(price) }}</div>
								</div>

								<div v-show="gyllingValin" class="uk-margin-top">
									<p><strong>Athugið:</strong> Gyllingin getur gefið skartinu hlýlegt yfirbragð. Hún máist þó af með tímanum. Gyllingin fæst án aukagjalds en án ábyrgðar um endingu.</p>
								</div>

								<div class="uk-margin-top">
									<button @click="addToCart" class="takki" :disabled="isProcessing">
										<span v-if=" ! isProcessing">
										Setja í körfu<i class="uk-icon-shopping-cart uk-margin-left"></i>
										</span>
										<span v-if="isProcessing">
										Augnablik... <i class="uk-icon-spin uk-icon-spinner uk-margin-left"></i>
										</span>
									</button><a v-if="added" href="/karfa/" class="takki" :disabled="isProcessing">
										Skoða körfu<i class="uk-icon-arrow-circle-o-right uk-margin-left"></i>
									</a>
								</div>
							</div>

							{{-- @if(count($colors) > 1)
								<div class="uk-grid uk-grid-small uk-margin-large-top" data-uk-grid-margin>
									<div class="uk-width-1-1">
										<strong>Litir</strong>
									</div>

					            	@foreach($colors as $color)
					            		<div class="product__aukamynd uk-width-small-2-10">
						            		<a title="{{ $color['title'] != $color['name'] ? $color['title'] : '' }}" href="/imagecache/original/{{ $color['name'] }}" data-uk-lightbox="{group:'litir'}"><img src="/imagecache/small/{{ $color['name'] }}" /></a>
						            	</div>
					            	@endforeach
					            </div>
							@endif --}}
						</div>
					</div>

					<!-- <pre>
					@{{ quantity | json 4 }}
					</pre> -->
				</div>
			</div>
		</div>
	</div>

	{{-- <div class="products uk-margin-large-top">

		<div class="uk-grid" data-uk-grid-margin data-uk-grid-height="{target:'.card'}">
		
			@if($siblings)
				@foreach($siblings as $item)

					@include('frontend._card', ['item' => $item])

				@endforeach
			@endif

		</div>

	</div> --}}
	
	<script>
	var product = new Vue({
		el: '#product-view',

		data: {
			base_price: {{ $item->price }},
			product_id: {{ $item->id }},
			options: {!! json_encode($item->options()->all(), true) !!},
			selected: [],
			quantity: 1,

			added: false,
			isProcessing: false
		},

		ready: function() {	
		},

        filters: {
            'qtyFilter': {
                read: function(val) {
                	if(val == 0) val = 1;

                    return val;
                },
                write: function(val, oldVal) {
                    var regex = /^\d{1,3}?$/;

                    if(regex.test(val)) {
                    	if(val == 0) val = 1;

                        return val
                    }

                    return oldVal;
                }
            }
        },

		computed: {
			gyllingValin: function() {
				var found = false;

				this.selected.filter(function(item) {
					if (item.value.text === 'Gylling' || item.value.text === 'Rósagylling' ? true : false) {
						found = true;
					}
				})

				return found;
			},

			price: function() {
				var price = this.base_price

				for(i in this.selected) {
					var modifier = 0;

					if( ! isNaN(this.selected[i].value.modifier)) {
						var modifier = parseInt(this.selected[i].value.modifier, 10);
						//console.log(modifier);

						if(modifier > 0 || modifier < 0) {
							price = price + (modifier)
						}
					}
				}

				price = price * this.quantity;

				return price
			}
		},

		methods: {
			addToCart: function() {
				this.isProcessing = true;
				this.$http.post('/_vorur/add-to-cart', { quantity: this.quantity, selected: this.selected, product_id: this.product_id }).then(function(response) {
					this.isProcessing = false;
					if(response.data.status == 'success') {
						cart_widget.increment(this.quantity);
						//cart_widget2.increment(this.quantity);
						this.added = true;
						UIkit.notify("<span class='uk-text-center'><i class='uk-icon-small uk-icon-check-circle uk-margin-right'></i>Vöru var bætt í <a href='/karfa/'>körfu</a>!</span>", { pos: 'bottom-center' })
					}
				}.bind(this));
			},


			priceFormatted: function(x) {
				var parts = x.toString().split(".");
				parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				return parts.join(".") +  ',- kr.';
			}
		}
	});
	</script>

@stop