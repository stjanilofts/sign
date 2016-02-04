<div v-cloak id="cart">
	<div class="cart-table-container">
		<div :class="{ 'updating-cart': ready, 'active': updatingCart }" class="uk-text-center" style="padding-top: 3em;">
			<i class="uk-icon-spin uk-icon-spinner uk-icon-large uk-margin-right" style="z-index: 100;"></i>Augnablik...
		</div>
		<table class="cart-table" v-if="ready && items.length > 0">
		    <thead>
		        <tr>
		        	<th style="width: 10%;"></th>
		            <th style="width: 50%;" class="uk-text-left">Vara</th>
		            <th style="width: 15%; min-width: 60px;" class="uk-text-right">Verð</th>
		            <th style="width: 10%; min-width: 60px;" class="uk-text-right">Magn</th>
		            <th style="width: 10%; min-width: 60px;" class="uk-text-right">Upphæð</th>
		            <th style="min-width: 60px;"></th>
		        </tr>
		    </thead>

		    <tbody>
		        <tr v-for="item in items">
		        	<td><a :href="'/vorur/' + item.path"><img :src="'/imagecache/small/' + item.image" /></a></td>
		            <td>
		            	<strong><a :href="'/vorur/' + item.path">@{{ item.name }}</a></strong>
		            	
		            	<ul class="cart-options-list" v-for="option in item.options">
		            		<li><strong>@{{ $key }}:</strong> @{{ option }}</li>
		            	</ul>
		            </td>
		            <td class="uk-text-right">@{{ item.price }}</td>
		            <td class="uk-text-right">
		            	<input class="grey-input uk-text-center" v-model="item.qty | qtyFilter item.qty" @keyDown="updateCart" size="2" />
		            </td>
		            <td class="uk-text-right">@{{ item.subtotal }}</td>
		            <td class="uk-text-center">
		            	<a style="font-size: 24px;" title="Fjarlægja úr körfu" style="pointer: cursor;" @click="itemRemove($index)">
		            		<i class="uk-icon-remove"></i>
		            	</a>
		            </td>
		       </tr>
		       <tr class="last">
		        	<td colspan="5" class="uk-text-right"><strong>Samtals @{{ total }}</strong></td>
		        	<td></td>
		       </tr>
		    </tbody>
		</table>
	</div>

	<div>
   		<a v-if=" ! updatePending && items.length > 0" class="takki" href="/checkout/">Ganga frá pöntun<i class="uk-margin-left uk-icon-arrow-circle-o-right"></i></a>
   </div>

	<div v-if="ready && items.length < 1" class="uk-text-center">
		<h3>Karfan er tóm!</h3>
		<p><a href="/vorur/">Kíktu á vörunar hér</a>.</p>
	</div>

	<!--<pre>
	@{{ items | json 4}}
	</pre>-->
</div>

<script>
var cart = new Vue({
	el: '#cart',

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