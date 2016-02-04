<div v-cloak id="checkout">
	<div v-if="!showForm" class="uk-text-center">
		<h3><i class="uk-icon-spin uk-icon-spinner uk-icon-large uk-margin-right"></i>Augnablik...</h3>
	</div>

	<form class="basic" @submit="onSubmit" v-if="showForm">
		<div class="uk-grid" data-uk-grid-margin>
			<div class="uk-width-1-1 uk-margin-large-top">
				<h3><i class="uk-icon-user uk-margin-right"></i>Þínar upplýsingar</h3>
			</div>

			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.nafn }">Nafn<br>
					<input class="uk-width-1-1" v-model="newOrder.nafn" type="text" name="nafn" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.nafn"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.nafn }}</small>
				</label>
			</div>
			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.netfang }">Netfang<br>
					<input class="uk-width-1-1" v-model="newOrder.netfang" type="text" name="netfang" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.netfang"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.netfang }}</small>
				</label>
			</div>
			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.simi }">Sími<br>
					<input class="uk-width-1-1" v-model="newOrder.simi" type="text" name="simi" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.simi"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.simi }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.heimilisfang }">Heimilisfang<br>
					<input class="uk-width-1-1" v-model="newOrder.heimilisfang" type="text" name="heimilisfang" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.heimilisfang"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.heimilisfang }}</small>
				</label>				
			</div>

			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.pnr }">Póstnúmer<br>
					<select class="uk-width-1-1" v-model="newOrder.pnr" name="pnr">
						<option value=""> - Veldu - </option>
						@foreach($pnr as $p)
							<option value="{{ $p }}">{{ $p }}</option>
						@endforeach
					</select>
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.pnr"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.pnr }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.stadur }">Staður<br>
					<input class="uk-width-1-1" v-model="newOrder.stadur" type="text" name="stadur" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.stadur"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.stadur }}</small>
				</label>
			</div>

			<div class="uk-width-1-1 uk-margin-large-top">
				<h3><i class="uk-icon-question-circle uk-margin-right"></i>Valkostir</h3>
			</div>
			
			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.greidslumati }">Greiðslumáti<br>
					<select class="uk-width-1-1" v-model="newOrder.greidslumati" name="greidslumati">
						<option value=""> - Veldu - </option>
						@foreach(config('formable.payment_options') as $k => $v)
							<option value="{{ $k }}">{{ $v }}</option>
						@endforeach
					</select>
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.greidslumati"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.greidslumati }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.afhendingarmati }">Afhendingarmáti<br>
					<select class="uk-width-1-1" v-model="newOrder.afhendingarmati" name="afhendingarmati">
						<option value=""> - Veldu - </option>
						@foreach(config('formable.shipping_options') as $k => $v)
							<option value="{{ $k }}">{{ $v }}</option>
						@endforeach
					</select>
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.afhendingarmati"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.afhendingarmati }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-1">
					<label>Eitthvað sem þú vilt taka fram?<br>
						<textarea class="uk-width-1-1" rows="5" name="athugasemd" v-model="newOrder.athugasemd"></textarea>
					</label>
				</div>
			</div>

			<div class="uk-width-1-1">
				<a href="/karfa/" class="takki" :disabled="submitDisabled"><i class="uk-icon-arrow-circle-o-left uk-margin-right"></i>Aftur í körfu</a>

				<button :disabled="submitDisabled" class="takki">
					<span v-if="!submitDisabled">Staðfesta pöntun<i class="uk-icon-thumbs-o-up uk-margin-left"></i></span>
					<span v-if="submitDisabled">Augnablik...<i class="uk-icon-spin uk-icon-spinner uk-margin-left"></i></span>
				</button>
			</div>
		</div>
	</form>
</div>

<script>
var checkout = new Vue({
	el: '#checkout',

	data: {
		submitDisabled: false,
		showForm: false,

		/*newOrder: {
	        nafn: 'Jón Jónsson',
	        netfang: 'test@netvistun.is',
	        simi: '414-5500',
	        heimilisfang: 'Test 123',
	        pnr: '123',
	        stadur: 'Test',
	        greidslumati: 'milli',
	        afhendingarmati: 'sent',
	        athugasemd: 'Engin athugasemd'
	    },*/

		newOrder: {
	        nafn: '',
	        netfang: '',
	        simi: '',
	        heimilisfang: '',
	        pnr: '',
	        stadur: '',
	        greidslumati: '',
	        afhendingarmati: '',
	        athugasemd: ''
	    },

	    errors: {
	        nafn: '',
	        netfang: '',
	        simi: '',
	        heimilisfang: '',
	        pnr: '',
	        stadur: '',
	        greidslumati: '',
	        afhendingarmati: '',
	        athugasemd: ''
	    }
	},

	ready: function() {
		this.cartHasItems();
	},

	methods: {
		onSubmit: function(e) {
			e.preventDefault();

			this.submitDisabled = true;

			this.onCreateOrder();
		},

		onOrderCreated: function(reference) {
			this.$http.get('/_vorur/cart-destroy').then(function(response) {
				if (response.status == 200) {
					window.location = '/order/' + reference;
				}
			}.bind(this));
		},

		cartHasItems: function() {
			this.$http.get('/_vorur/cart-has-items').then(function(response) {
				if (response.status == 200) {
					if (response.data.itemCount > 0) {
						this.showForm = true;
					} else {
						window.location = '/karfa/';
					}
				}
			}.bind(this));
		},

		onCreateOrder: function() {
			this.$http.get('/_vorur/cart-has-items').then(function(response) {
				if (response.status == 200) {
					if (response.data.itemCount > 0) {
						this.createOrder();
					} else {
						window.location = '/karfa/';
					}
				}
			}.bind(this));
		},

		createOrder: function() {
			this.$http.post('/_order/create-order', this.newOrder).then(function(response) {
				if (response.status == 200) {
					if (response.data.reference.length > 0) {
						this.onOrderCreated(response.data.reference);
					} else {
						alert('did not recieve reference');
					}
				}
			}.bind(this), function(response) {
				this.$set('errors', response.data);
				this.submitDisabled = false;
			}.bind(this));
		}
	}
});
</script>