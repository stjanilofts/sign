<div class="uk-form-row">
	<label class="uk-form-label" for="status">Foreldri</label>
	<div class="uk-form-controls">
		{!! Form::select($item->parent_key, $parents, (isset($item->id) ? $item->{$item->parent_key} : isset($selectedParentId) ?: 0)) !!}
	</div>
</div>