<?php

$hlutir = array();
foreach(config('formable.hlutir') as $hlutur) {
	$hlutir[trim(strtolower($hlutur))] = ucfirst(trim($hlutur));
}

?>
<div class="uk-form-row">
	<label class="uk-form-label" for="status">Hlutur sem þessi efnislinkur beinir á (ef við á).</label>
	<div class="uk-form-controls">
		{!! Form::select('hlutur', $hlutir, (isset($item->id) ? $item->hlutur : 'efni')) !!}
	</div>
</div>

<div class="uk-form-row">
	<label class="uk-form-label" for="parent_id">Foreldri</label>
	<div class="uk-form-controls">
		{!! Form::select('parent_id', $parents, (isset($item->parent_id) ? $item->parent_id : (isset($selectedParentId) ? $selectedParentId : 0))) !!}
	</div>
</div>

@if(!empty($images))
	<div class="uk-form-row">
		<label class="uk-form-label" for="banner">Banner mynd</label>
		<div class="uk-form-controls">
			{!! Form::select('banner', $images, (isset($item->banner) ? $item->banner : ''), ['class'=>'uk-width-1-1']) !!}
		</div>
		@if(isset($item->banner))
			<div>
				<p><img class="banner-image" src="/imagecache/small/{{ $item->banner }}" /></p>
			</div>
		@endif
	</div>
	<script>
	$(document).ready(function() {
		$(function() {
			$('select[name=banner]').change(function() {
				$('img.banner-image').attr('src', '/imagecache/small/' + $(this).val())
			})
		})
	})
	</script>
@endif