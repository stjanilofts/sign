@extends('admin.layout')

@section('content')

	<style>
	.disable-pointer-events {
		/*outline: 2px solid red;
		border: 2px solid blue;*/
		pointer-events: none !important;
		opacity: 0.3;
		transition: opacity 0.3s;
	}
	</style>
	
	{!! $breadcrumbs->render() !!}

	<hr>

	@if (isset($item))
		<h2>{{ $item->title }}</h2>			
	@endif

	<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#formable'}">
	    <li><a href="?#content">Efni</a></li>

	    @if(isset($item))
	    	@if(in_array('App\Traits\ProductOptionsTrait', class_uses($item)))
				<li><a href="?#options">Vöruvalmöguleikar</a></li>
	    	@endif
	    	
	    	<li><a href="?#images">Myndir</a></li>
	    	<li><a href="?#files">Skrár</a></li>

	    	{{--@if(strtolower($item->modelName())=='product')
	    		<li><a href="?#shell">Lok</a></li>
	    	@endif

	    	@if(strtolower($item->modelName())=='product')
	    		<li><a href="?#skirt">Litir</a></li>
	    	@endif--}}

	    @endif

	</ul>

	<ul id="formable" class="uk-switcher">
	    <li>
			@if(isset($item))
				
				{!! Form::model($item, array('class' => 'uk-form uk-form-stacked', 'route' => array('admin.'.strtolower($item->modelName()).'.update', $item->id), 'method' => 'PATCH')) !!}
				
					@if (File::exists('../resources/views/admin/'.strtolower($item->modelName()).'/_form.blade.php'))
			    		@include('admin.'.strtolower($item->modelName()).'._form', ['item' => $item])
			    	@endif

					@include('admin.formable._form')

			    	<div class="uk-form-row">
						<button class="uk-button uk-button-primary">Vista<i class="uk-icon-save uk-margin-left"></i></button>
					</div>
				
				{!! Form::close() !!}

			@else

				{!! Form::model($model, array('class' => 'uk-form uk-form-stacked', 'route' => 'admin.'.strtolower($model->modelName()).'.store', 'method' => 'POST')) !!}

					@if (File::exists('../resources/views/admin/'.strtolower($model->modelName()).'/_form.blade.php'))
			    		@include('admin.'.strtolower($model->modelName()).'._form', ['item' => $model])
			    	@endif

					@include('admin.formable._form', ['item'=>$model])

			    	<div class="uk-form-row">
						<button class="uk-button uk-button-primary">Vista<i class="uk-icon-save uk-margin-left"></i></button>
					</div>
				
				{!! Form::close() !!}

			@endif

		</li>

		@if(isset($item))
	    	@if(in_array('App\Traits\ProductOptionsTrait', class_uses($item)))
				<li>
					@include('admin.product._options', ['item' => $item])
				</li>
	    	@endif

			<li>

				@include('admin.formable._uploader', ['item' => $item, 'field' => 'images', 'token' => csrf_token()])

			</li>

			<li>

				@include('admin.formable._fileuploader', ['item' => $item])

			</li>

			@if(strtolower($item->modelName())=='product')
				<li>

					@include('admin.formable._uploader', ['item' => $item, 'field' => 'shell', 'token' => csrf_token()])

				</li>

				<li>

					@include('admin.formable._uploader', ['item' => $item, 'field' => 'skirt', 'token' => csrf_token()])

				</li>
			@endif
		
		@endif
	</ul>

	<script>
	/*$('[data-uk-switcher]').on('show.uk.switcher', function(event, area){
	    active:2
	});*/
	</script>

@stop