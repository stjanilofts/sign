<div id="skraarsafn">
	<form action="/file-upload"
	      class="dropzone"
	      id="formable-file-uploader">
	      {!! csrf_field() !!}
	      <input type="hidden" name="model" value="{!! strtolower(trim($item->modelName())) !!}">
	      <input type="hidden" name="id" value="{!! (int)$item->id !!}">
	</form>

	<div v-if="isUploading" class="uk-margin-top uk-margin-bottom"><i class="uk-icon-large uk-icon-spin uk-icon-refresh uk-margin-right"></i>Augnablik...</div>

	<h3>Skráarsafn</h3>
	<p v-if="! files.length">Engar skrár komnar inn.</p>
	<p>Settu inn skrár í reitinn fyrir ofan.</p>
	<ul class="uk-sortable files" :class="{ 'disable-pointer-events': isUploading }" data-uk-sortable>
		<li v-for="file in files" style="cursor: move; padding: 20px; border-bottom: 1px solid #EEE; display: block;" data-file-name="@{{ file.name }}" data-file-title="@{{ file.title }}">
			<div v-if="file.name">
				<div>
					<small>
						<strong>@{{ $index + 1 }}.</strong><br>
						<strong>Heiti:</strong><br>
						<input @blur="reorderImages()" @blur="tester" v-model="file.title" style="width: 90%;" /><br>
						<strong>Slóð til að setja með hlekkjum á síðu:</strong><br>
						<input value="/files/@{{ file.name }}" style="width: 90%" readonly /><br>
						<strong>Full slóð með léni:</strong><br>
						<input style="width: 90%"  value="{{ \Request::root() }}/files/@{{ file.name }}" readonly />
					</small>
				</div>
			</div>
		</li>
	</ul>
</div>

<script>
	window.sortablefiles = UIkit.sortable($('ul.uk-sortable.files'), {});

	var fileVue = new Vue({
		el: '#skraarsafn',

		data: {
			isUploading: false,

			files: [],

			item: {
				model: $('input[name=model]').val(),
				id: $('input[name=id]').val()
			}
		},

		ready: function() {
			var self = this;
			
			this.updateFileList();

			$('#skraarsafn').on('stop.uk.sortable', function() {
				self.isUploading = true;
				self.reorderFiles();
			});

			
			Dropzone.options.formableFileUploader = {
				init: function() {
					this.on("addedfile", function(file) {
						console.log('added file');
						self.isUploading = true;
					});

					this.on("complete", function(file) {
						console.log('complete');
						self.updateFileList();
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
				dictDefaultMessage: 'Dragðu skrár yfir í þennan reit til að setja inn.',
				url: '/admin/formable/_uploadFile',
				paramName: "file",
				maxFilesize: 2,
				acceptedFiles: '.pdf, .doc, .docx, .xml, .txt, .odt',
				
				accept: function(file, done) {
					self.updateFileList();
					done();
				}
			};
		},
		
		methods: {
			tester: function(e) {
				var $el = $(e.target);
				$el.addClass('uk-form-success');
			},
			
			reorderFiles: function() {
				var self = this;				

				var newFilesList = [];

				$.each($('ul.uk-sortable.files').find('li'), function(i, v) {
					var name = $(v).attr('data-file-name');
					var title = $(v).attr('data-file-title');
					newFilesList.push({name: name, title: title});
				});

				console.log(newFilesList);

				if(newFilesList.length > 0) {
					var item = {
						model: $('input[name=model]').val(),
						id: $('input[name=id]').val(),
						files: newFilesList
					};

		   			this.$http.post('/admin/formable/_reorderFiles', item).then(function (response) {
		   				self.updateFileList();
		   			});
				}
			},

			updateFileList: function() {
				var self = this;

				this.$http.post('/admin/formable/_files', self.item).then(function (response) {
					self.isUploading = false;
					this.$set('files', response.data);
				});
			}
		}
	});
</script>