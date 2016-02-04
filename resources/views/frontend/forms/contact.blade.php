<div id="contact-form">
	<form class="basic" v-on:submit.prevent="onSubmit" v-if="!isSubmitted">
		<div class="uk-grid" data-uk-grid-margin>
			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.nafn }">Nafn<br>
					<input id="fyrirspurn_nafn"
						   @focus="clearError"
						   type="text"
						   class="uk-width-1-1"
						   :disabled="isSubmitting"
						   name="nafn"
						   v-model="fyrirspurn.nafn" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.nafn"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.nafn }}</small>
				</label>
			</div>
			
			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.netfang }">Netfang<br>
					<input id="fyrirspurn_netfang"
						   @focus="clearError"
						   type="text"
						   class="uk-width-1-1"
						   :disabled="isSubmitting"
						   name="netfang"
						   v-model="fyrirspurn.netfang" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.netfang"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.netfang }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.simi }">Símanúmer<br>
					<input id="fyrirspurn_simi"
						   @focus="clearError"
						   type="text"
						   class="uk-width-1-1"
						   :disabled="isSubmitting"
						   name="simi"
						   v-model="fyrirspurn.simi" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.simi"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.simi }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-2">
				<label :class="{ 'errors': errors.titill }">Titill á skilaboðum<br>
					<input id="fyrirspurn_titill"
						   @focus="clearError"
						   type="text"
						   class="uk-width-1-1"
						   :disabled="isSubmitting"
						   name="titill"
						   v-model="fyrirspurn.titill" />
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.titill"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.titill }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-1">
				<label :class="{ 'errors': errors.skilabod }">Skilaboðin þín<br>
					<textarea id="fyrirspurn_skilabod"
							  @focus="clearError"
							  rows="6"
							  class="uk-width-1-1"
							  :disabled="isSubmitting"
							  name="skilabod"
						   	  v-model="fyrirspurn.skilabod"></textarea>
					<small class="uk-display-inline-block uk-margin-small-top" v-if="errors.skilabod"><i class="uk-icon-exclamation-triangle uk-margin-right"></i>@{{ errors.skilabod }}</small>
				</label>
			</div>

			<div class="uk-width-medium-1-1">
				<button class="takki larger"
						:disabled="isSubmitting || errors.length">
					<span v-if="isSubmitting">
						Er að senda<span style="width: 20px; display: inline-block; text-align: left;">@{{ dots }}</span>
					</span>

					<span v-if="!isSubmitting">
						Senda skilaboð
					</span>
				</button>
			</div>
		</div>
	</form>

	<div v-if="isSubmitted">
		<h3>Takk fyrir!</h3>
		<p>Við munum fara yfir skilaboðin þín og vera í bandi.</p>
	</div>
</div>

<script>
var contact_form = new Vue({
	el: '#contact-form',
	
	data: {
		dots: '',
		timer: false,
		interval: false,
		isSubmitting: false,
		isSubmitted: false,

		fyrirspurn: {
			nafn: '',
			netfang: '',
			simi: '',
			titill: '',
			skilabod: ''
		},

		errors: {
			nafn: '',
			netfang: '',
			simi: '',
			titill: '',
			skilabod: ''
		},

		button_text: 'Skrá mig'
	},

	ready: function() {
		this.isSubmitting = false;
		this.isSubmitted = false;
	},


    methods: {
    	clearError: function(e) {
    		var $name = e.target.getAttribute('name');
    		this.errors[$name] = '';
    	},

    	hasError: function(name) {
    		return (this.errors.hasOwnProperty(name) && this.errors[name].length);
    	},

    	onSubmit: function(e) {
    		var self = this;

    		clearTimeout(self.timer);
    		clearInterval(self.interval);

    		self.isSubmitted = false;
    		self.isSubmitting = true;

    		self.errors = [];

    		self.interval = setInterval(function() {
    			self.dots = self.dots + '.';

    			if(self.dots.length == 4) {
    				self.dots = '';
    			}
    		}, 250);

    		self.timer = setTimeout(function() {
    			self.$http.post('/hafa-samband', self.fyrirspurn).then(function(response) {
    				//console.log('done', data, status, request);
	    			self.isSubmitted = true;
	    			self.isSubmitting = false;
	    		}, function(response) {
	    			self.$set('errors', response.data);
	    			self.isSubmitting = false;
	    		});
	    	}, 1000);
    	}
    }
});
</script>