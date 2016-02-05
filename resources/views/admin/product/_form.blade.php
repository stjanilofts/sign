<div class="uk-form-row">
	<label class="uk-form-label" for="status">Flokkur</label>
	<div class="uk-form-controls">
		{!! Form::select($item->parent_key, $flokkar, (isset($item->id) ? $item->{$item->parent_key} : isset($selectedFlokkurId) ? $selectedFlokkurId : 0)) !!}
	</div>
</div>

<?php
$collections = \App\Product::collections();
$cols = array_merge($collections, ['' => ' - Veldu línu (ef á við) - ']);
?>
<div class="uk-form-row">
	<label class="uk-form-label" for="collection">Collection (Lína)</label>
	<div class="uk-form-controls">
		{!! Form::select('collection', $cols, (isset($item->collection) ? $item->collection : '')) !!}
	</div>
</div>

<div class="uk-form-row">
	<label class="uk-form-label" for="konur">
		{!! Form::checkbox('konur', (isset($item->konur) ? old($item->konur) : '')) !!}
	 Fyrir konur</label>
</div>

<div class="uk-form-row">
	<label class="uk-form-label" for="karlar">
		{!! Form::checkbox('karlar', (isset($item->karlar) ? old($item->karlar) : '')) !!}
	 Fyrir karla</label>
</div>