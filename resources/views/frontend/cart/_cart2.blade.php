<div v-cloak id="cart2">
	<div class="cart2-container">
		<div :class="{ 'updating-cart': ready, 'active': updatingCart }" v-cloak>
			<div class="uk-height-1-1 uk-width-1-1 uk-flex uk-flex-middle uk-flex-center">
				<i class="uk-icon-refresh uk-icon-spin uk-icon-large uk-margin-right" style="z-index: 100;"></i>Augnablik
			</div>
		</div>

		<div class="cart2" v-cloak v-if="ready && items.length > 0">
			<div class="uk-grid uk-grid-collapse uk-hidden-small">
				<div class="uk-width-9-10">
					<div class="uk-grid uk-grid-collapse">
						<div class="uk-width-1-10">
							<div class="column uk-text-center">
								&nbsp;
							</div>
						</div>

						<div class="uk-width-4-10">
							<div class="column uk-text-bold">
								Vara
							</div>
						</div>

						<div class="uk-width-2-10">
							<div class="column uk-text-center uk-text-bold">
								Verð
							</div>
						</div>

						<div class="uk-width-1-10">
							<div class="column uk-text-center uk-text-bold">
								Magn
							</div>
						</div>

						<div class="uk-width-2-10">
							<div class="column uk-text-center uk-text-bold">
								Upphæð
							</div>
						</div>
					</div>
				</div>


				<div class="uk-width-9-10">
				</div>
			</div>

			<div v-for="item in items" class="CartItem">
				<div class="uk-grid uk-grid-collapse" data-uk-grid-match>
					<div class="uk-width-9-10">
						<div class="inner">
							<div class="uk-grid uk-grid-collapse">
								<div class="uk-width-medium-1-10 uk-flex uk-flex-middle uk-hidden-small">
									<div class="column">
										<a :href="'/vefverslun/' + item.path"><img :src="'/imagecache/small/' + item.image" /></a>
									</div>
								</div>

								<div class="uk-width-medium-4-10 uk-flex uk-flex-middle">
									<div class="column">
										<h5><a :href="'/vefverslun/' + item.path">@{{ item.name }}</a></h5>

										<ul class="cart-options-list" v-for="option in item.options">
							        		<li><strong>@{{ $key }}</strong> @{{ option }}</li>
							        	</ul>
							        </div>
								</div>

								<!--// Verð -->
								<div class="uk-width-medium-2-10 uk-flex uk-flex-middle uk-flex-center uk-hidden-small">
									<div class="column">
										@{{ item.price }}
									</div>
								</div>
								<div class="uk-width-medium-2-10 uk-visible-small">
									<div class="column">
										<strong>Verð</strong> @{{ item.price }}
									</div>
								</div>

								<!--// Magn -->
								<div class="uk-width-medium-1-10 uk-flex uk-flex-middle uk-flex-center uk-hidden-small">
									<div class="column">
										<input class="grey-input uk-text-center" v-model="item.qty | qtyFilter item.qty" @keyDown="updateCart" size="2" />
									</div>
								</div>
								<div class="uk-width-medium-1-10 uk-visible-small">
									<div class="column">
										<strong>Magn</strong> <input class="grey-input uk-text-center" v-model="item.qty | qtyFilter item.qty" @keyDown="updateCart" size="2" />
									</div>
								</div>



								<!--// Upphæð -->
								<div class="uk-width-medium-2-10 uk-flex uk-flex-middle uk-flex-center uk-hidden-small">
									<div class="column">
										<strong class="uk-margin-right uk-visible-small">Upphæð</strong>@{{ item.subtotal }}
									</div>
								</div>
								<div class="uk-width-medium-2-10 uk-visible-small">
									<div class="column">
										<strong>Upphæð</strong> @{{ item.subtotal }}
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="uk-width-1-10 uk-flex uk-flex-middle uk-flex-center">
						<div class="column">
				        	<a style="font-size: 24px;" title="Fjarlægja úr körfu" style="pointer: cursor;" @click="itemRemove($index)">
			        			<i class="uk-icon-trash-o"></i>
		        			</a>
		        		</div>
					</div>
				</div>
			</div>			



			<div class="CartItem">
				<div class="uk-grid uk-grid-collapse" data-uk-grid-match>
					<div class="uk-width-1-1">
						<div class="column uk-text-right">
							<strong>Samtals @{{ total }}</strong>
						</div>
					</div>
				</div>
			</div>
						
		</div>
	</div>

	<div class="uk-margin-top">
   		<a v-if=" ! updatePending && items.length > 0" class="takki" href="/checkout/">Ganga frá pöntun<i class="uk-margin-left uk-icon-arrow-circle-o-right"></i></a>
	</div>

	<div v-if="ready && items.length < 1" class="uk-text-center">
		<h3>Karfan er tóm!</h3>
		<p><a href="/vefverslun/">Kíktu á vörunar hér</a>.</p>
	</div>

	<!--<pre>
	@{{ items | json 4}}
	</pre>-->
</div>

<script>
var cart = new Vue({
	el: '#cart2',

	data: {
		ready: false,
		updatingCart: true,
		updatePending: false,
		title: 'title',
		total: 0,
		//items: {!! json_encode(cartItems(), true) !!}
		//items: cartStore.state.items,
		items: [],
		updateCartTimer: false
	},

	ready: function() {
		this.getCartItems();
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
                    return val
                }

                return oldVal;
            }
        }
    },

	methods: {
		getCartItems: function() {			
			this.$http.get('/_vorur/get-cart-items').then(function(response) {
				this.$set('items', response.data.items);
				this.$set('total', response.data.total);
				this.ready = true;
				cart_widget.items.items = this.items;
				this.updatingCart = false;
				this.updatePending = false;
			}.bind(this));
		},

		updateCart: function() {
			this.updatePending = true;

			clearTimeout(this.updateCartTimer);

			this.updateCartTimer = setTimeout(function() {
				this.updatingCart = true;

				this.$http.post('/_vorur/update-cart', { items: this.items }).then(function(response) {
					this.getCartItems();
				}.bind(this));
			}.bind(this), 1000);
		},

		itemRemove: function(idx) {
			this.updatingCart = true;
			this.items.splice(idx, 1);
			this.updateCart();
		}
	}
});
</script>