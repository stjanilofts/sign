@if( ! isset($field))
	<h3>Field setting missing</h3>
@else
	<div id="{{ $field }}">
		<form action="/file-upload"
		      class="dropzone"
		      id="formable-image-uploader-{{ $field }}">
		      <input type="hidden" name="_token" value="{{ $token }}">
		      <input type="hidden" name="model" value="{!! strtolower(trim($item->modelName())) !!}">
		      <input type="hidden" name="id" value="{!! (int)$item->id !!}">
	      	  <input type="hidden" name="field" value="{{ $field }}">
		</form>

		<div v-if="isUploading" class="uk-margin-top uk-margin-bottom"><i class="uk-icon-large uk-icon-spin uk-icon-refresh uk-margin-right"></i>Augnablik...</div>

		<h3>Myndasafn</h3>
		<p v-if="! {{ $field }}.length">Engar myndir komnar inn.</p>
		<p>Eftir að búið er að upphala myndum í reitinn hér að ofan, þá birtast þær í listanum hér að neðan.</p>
		<ul class="uk-sortable {{ $field }} uk-grid uk-grid-small uk-grid-width-medium-1-4 uk-grid-width-large-1-6 uk-grid-width-small-1-3" :class="{ 'disable-pointer-events': isUploading }" data-uk-sortable>
			<li class="uk-grid-margin" v-for="image in {{ $field }}" style="cursor: move;" data-file-name="@{{ image.name }}" data-file-title="@{{ image.title }}">
				<div v-if="image.name" class="uk-panel uk-panel-box">
					<div>
						<img v-if="image.name" :src="'/imagecache/medium/' + image.name" />
					</div>
					<div>
						<form class="uk-form uk-margin-top">
							<input class="uk-margin-small-bottom" @blur="reorderImages()" @blur="tester" v-model="image.title"><br>
							<a @click="deleteImage($index)"><i class="uk-icon-trash-o"></i></a>
						</form>
					</div>
				</div>
			</li>
		</ul>
	</div>

	<script>
		$(function(){
			window.sortable{{ $field }} = UIkit.sortable($('ul.uk-sortable.{{ $field }}'), {});

			var {{ $field }}View = new Vue({
				el: '#{{ $field }}',

				data: {
					isUploading: false,

					{{ $field }}: [],

					item: {
						model: $('input[name=model]').val(),
						id: $('input[name=id]').val(),
						field: '{{ $field }}'
					}
				},

				ready: function() {
					var self = this;

					this.updateImageList();

					$('#{{ $field }}').on('stop.uk.sortable', function() {
						self.isUploading = true;
						self.reorderImages();
					});

				

					Dropzone.options.formableImageUploader{{ ucfirst($field) }} = {
						init: function() {
							this.on("addedfile", function(file) {
								//console.log('added file');
								self.isUploading = true;
							});

							this.on("complete", function(file) {
								//console.log('complete');
								self.updateImageList();
								self.isUploading = false;
							});

							this.on("removedfile", function(file) {
								//alert("Removed file.");
							});
						},

						dictCancelUploadConfirmation: 'Ertu viss um að þú viljir hætta við?',
						dictMaxFilesExceeded: 'Ekki var hægt að bæta við fleiri skrám í þessari lotu!',
						dictRemoveFile: 'Skrá var fjarlægð!',
						dictCancelUpload: 'Hætt var við upphalningu!',
						dictResponseError: 'Villa kom upp!',
						dictInvalidFileType: 'Þessi skrá er ekki leyfileg!',
						dictFileTooBig: 'Myndin er of stór!',
						dictDefaultMessage: 'Dragðu myndir yfir í þennan reit til að setja inn.',
						url: '/admin/formable/_uploadImage',
						paramName: "photo",
						maxFilesize: 2,
						acceptedFiles: '.jpg, .jpeg, .png, .gif',
						
						accept: function(file, done) {
							self.updateImageList();
							done();
						}
					};
				},
				
				methods: {
					tester: function(e) {
						var $el = $(e.target);
						$el.addClass('uk-form-success');
					},

					deleteImage: function(idx) {
						var self = this;

						var {{ $field }}_data = {
							model: $('input[name=model]').val(),
							id: $('input[name=id]').val(),
							field: '{{ $field }}',
							idx: idx + 1
						};

						UIkit.modal.confirm("Ertu viss um að þú viljir eyða?", function() {
							self.isUploading = true;

				   			self.$http.post('/admin/formable/_deleteImage', {{ $field }}_data).then(function (response) {
								self.updateImageList();
							});
						});
					},

					reorderImages: function() {
						var self = this;				

						var newImagesList = [];

						$.each($('ul.uk-sortable.{{ $field }}').find('li'), function(i, v) {
							var name = $(v).attr('data-file-name');
							var title = $(v).attr('data-file-title');
							newImagesList.push({name: name, title: title});
						});

						//console.log(newImagesList);

						if(newImagesList.length > 0) {
							var item = {
								model: $('input[name=model]').val(),
								id: $('input[name=id]').val(),
								field: '{{ $field }}',
								{{ $field }}: newImagesList
							};

				   			this.$http.post('/admin/formable/_reorderImages', item).then(function (response) {
								//console.log('done');
								self.updateImageList();
							});
						}
					},

					updateImageList: function() {
						this.$http.post('/admin/formable/_images', this.item).then(function (response) {
							this.isUploading = false;
							//console.log(this.item.field)
							this.$set('{{ $field }}', response.data);
						}.bind(this));
					}
				}
			});
		});
	</script>
@endif