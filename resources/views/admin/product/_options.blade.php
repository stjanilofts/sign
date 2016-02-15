<div v-cloak id="admin-product-options">

	<div v-for="option in options" class="uk-margin-bottom">
		<div class="uk-panel uk-panel-box" :class="{ 'uk-panel-box-primary': option.readonly > 0 }">
			<form class="uk-form uk-form-stacked"
				  @submit="onSubmit">
				<div class="uk-grid uk-grid-small" data-uk-grid-margin>
					<div class="uk-width-9-10">
						<input :disabled="option.readonly > 0" class="uk-form-large uk-form-controls uk-width-1-1" v-model="option.text" placeholder="Valflokkaheiti" />
					</div>

					<div class="uk-width-1-10">
						<a v-if="!option.readonly" :disabled="option.readonly > 0" @click="removeOption($index)"><i class="uk-icon-medium uk-icon-remove"></i></a>
					</div>
				</div>

				<div v-if="option.values.length > 0 || option.text.length > 0" class="uk-margin-top">
					<div class="uk-grid uk-grid-small" data-uk-grid-margin v-for="value in option.values">
						<div class="uk-width-7-10">
							<input :disabled="option.readonly > 0" class="uk-form-small uk-width-1-1" v-model="value.text" placeholder="Valmöguleiki">
						</div>

						<div class="uk-width-2-10">
							<input :disabled="option.readonly > 0" class="uk-form-small uk-width-1-1" v-model="value.modifier" placeholder="Mismunur (+/-)">
						</div>

						<div class="uk-width-1-10">
							<a v-if="!option.readonly" :disabled="option.readonly > 0" @click="removeSubOption($parent.$index, $index)"><i class="uk-icon-small uk-icon-remove"></i></a>
						</div>
					</div>

					<button :disabled="option.readonly > 0" class="uk-button uk-margin-top uk-button-small" @click="addSubOption($index)">Nýr valmöguleiki<i class="uk-icon-plus uk-margin-left"></i></button>
				</div>
			</form>
		</div>
	</div>

	<button class="uk-button uk-margin-bottom uk-button-primary" @click="addOption">Nýr valflokkur<i class="uk-icon-plus uk-margin-left"></i></button>
	<button class="uk-button uk-margin-bottom uk-button-success uk-margin-left"
			@click="saveOptions">Vista breytingar<i class="uk-icon-save uk-margin-left"></i></button>

	<div class="uk-clearfix"></div>

	<p v-if="saving"><i class="uk-icon-spin uk-icon-spinner"></i> Vista...</p>

	{{--
	<pre>
	@{{ options | json }}
	</pre>
	--}}
	
	
</div>

<script>
Vue.config.debug = true

new Vue({
	el: '#admin-product-options',

	data: {
		saving: false,
		changed: false,
		options: []
	},

	ready: function() {
		this.$http.get('/_product/{{ $item->id }}').then(function(response) {
			self.optionCount = response.data.length;

			this.$set('options', response.data);
		});
	},

	methods: {
		onChange: function() {
			this.changed = true;
		},

		saveOptions: function() {
			this.saving = true;

			var self = this;

			var data = {
				options: this.options
			};

			$.each(self.options, function(i, v) {
				if(!v.text.length) {
					v.values = [];
				}
			});

			this.$http.post('/_product/{{ $item->id }}/save-options', data).then(function(response) {
				self.optionCount = response.data.length;
				this.$set('options', response.data);
				self.saving = false;
			});
		},

		onSubmit: function(e) {
			e.preventDefault();
		},

		addOption: function() {
			var newOption = this.newOption;

			this.options.push({
				text: '',
				readonly: 0,
				type: 'select',
				values: []
			});
		},

		removeOption: function($idx) {
			//console.log('removing ' + $idx);
			this.options.splice($idx, 1);
		},

		addSubOption: function($idx) {
			var newValue = this.newValue;

			this.options[$idx].values.push({
				text: '',
				value: '',
				modifier: ''
			});
		},

		removeSubOption: function($parIdx, $subIdx) {
			//console.log('removing ' + $parIdx + ', ' + $subIdx);
			this.options[$parIdx].values.splice($subIdx, 1);
		}
	}
});
</script>