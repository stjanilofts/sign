<div v-cloak id="{{ $elementId }}" class="{{ \Request::is('karfa*') ? 'active being_viewed' : '' }}">
	<a :title="count > 0 ? count + ' hlut' + ((count > 1) ? 'i' : 'u') + 'r í körfunni' : 'Karfan þín'" href="/karfa/" class="{{ $elementId }}__link">
		<i class="uk-icon-shopping-cart uk-icon-small"></i>
		<span v-if="count > 0" class="uk-margin-left">@{{ count + (count > 1 ? ' vörur' : ' vara') }}</span>
		<span v-if="count < 1" class="uk-margin-left">Karfa</span>
	</a>
</div>
<script>
var {{ $elementId }} = new Vue({
	el: '#{{ $elementId }}',

	data: {
		base: 0,
		items: {!! cartItems() !!}
	},

	ready: function() {
		$(document).ready(function() {
			setTimeout(function() {
				this.getCartItems();
			}.bind(this), 1);	
		}.bind(this));
	},

	methods: {
		increment: function(qty) {
			this.base = this.base + parseInt(qty, 10)
		},
		getCartItems: function() {
			this.$http.get('/_vorur/get-cart-items').then(function(response) {
				this.$set('items.items', response.data.items);
			}.bind(this));
		}
	},

	computed: {
		count: function() {
			var count = this.base

			this.items.items.filter(function(item) {
				count = count + parseInt(item.qty, 10)
			})				

			return count
		}
	}
})
</script>