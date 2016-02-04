<div class="uk-form-row">
	<label class="uk-form-label" for="status">Flokkur</label>
	<div class="uk-form-controls">
		{!! Form::select($item->parent_key, $flokkar, (isset($item->id) ? $item->{$item->parent_key} : isset($selectedFlokkurId) ? $selectedFlokkurId : 0)) !!}
	</div>
</div>