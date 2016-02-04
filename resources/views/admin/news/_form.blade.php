<div class="uk-form-row">
	<label class="uk-form-label" for="type">Tegund</label>
	<div class="uk-form-controls">
		{!! Form::select('type', $types, isset($item->id) ? $item->type : \Request::get('type') ? \Request::get('type') : 'news') !!}
	</div>
</div>

@if(!empty($images))
	<div class="uk-form-row">
		<label class="uk-form-label" for="banner">Banner mynd</label>
		<div class="uk-form-controls">
			{!! Form::select('banner', $images, (isset($item->banner) ? $item->banner : ''), ['class'=>'uk-width-1-1']) !!}
		</div>
	</div>
@endif