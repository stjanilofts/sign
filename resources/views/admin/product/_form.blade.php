<div class="uk-form-row">
	<label class="uk-form-label" for="status">Flokkur</label>
	<div class="uk-form-controls">
		{!! Form::select($item->parent_key, $flokkar, (isset($item->id) ? $item->{$item->parent_key} : isset($selectedFlokkurId) ? $selectedFlokkurId : 0)) !!}
	</div>
</div>

<?php
$collections = \App\Product::collections();
$cols = ['' => ' - Veldu línu (ef á við) - '];
foreach($collections as $key => $collection) {
	$cols[$key] = $collection['title'];
}

$product_types = \App\Product::product_types();


?>
<div class="uk-form-row">
	<label class="uk-form-label" for="collection">Collection (Vörulína)</label>
	<div class="uk-form-controls">
		{!! Form::select('collection', $cols, (isset($item->collection) ? $item->collection : '')) !!}
	</div>
</div>

<div class="uk-form-row">
	<label class="uk-form-label" for="product_type">Tegund vöru</label>
	<div class="uk-form-controls">
		{!! Form::select('product_type', $product_types, (isset($item->product_type) ? $item->product_type : '')) !!}
		<br><small>Þetta segir til um hvaða vöruvalmöguleikar eru fyrirfram ákveðnir.</small>
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