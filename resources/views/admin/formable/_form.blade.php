@foreach($item->fields() as $field)
	@if($field['type']=='date')
		<div class="uk-form-row">
			<label class="uk-form-label" for="{{ $field['name'] }}">{{ $field['title'] }}</label>

			<div class="uk-form-controls">
				{!! Form::text(
						$field['name'],
						(isset($item->{$field['name']}) ? old($item->{$field['name']}) : ''),
						array(
							'id'=>$field['name'],
							(isset($field['args']['disabled']) && $field['args']['disabled'] ? 'disabled' : ''),
							'class'=>'uk-width-1-1',
							'data-uk-datepicker'=>"{format:'YYYY-MM-DD'}"
						)
					)
				!!}
			</div>
		</div>
	@endif

	@if($field['type']=='checkbox')
		<div class="uk-form-row">
			<label class="uk-form-label" for="{{ $field['name'] }}">
				{!! Form::checkbox(
						$field['name'],
						(isset($item->{$field['name']}) ? old($item->{$field['name']}) : '')
					)
				!!} 
			 {{ $field['title'] }}</label>
			 <script>
			 $(document).ready(function() {
			 });
			 </script>
		</div>
	@endif

	@if($field['type']=='text')
		@if(! in_array($field['name'], $item->translatable))
			<div class="uk-form-row">
				<label class="uk-form-label" for="{{ $field['name'] }}">{{ $field['title'] }}</label>

				<div class="uk-form-controls">
					{!! Form::text(
							$field['name'],
							(isset($item->{$field['name']}) ? old($item->{$field['name']}) : ''),
							array(
								'id'=>$field['name'],
								(isset($field['args']['disabled']) && $field['args']['disabled'] ? 'disabled' : ''),
								'class'=>'uk-width-1-1')
							)
					!!}
				</div>
			</div>
		@else
			@foreach(config('formable.locales') as $locale)
				<div class="uk-form-row">
					<label class="uk-form-label" for="{{ $field['name'].'_'.$locale }}"><img src="/{!! config('formable.flagicons.'.$locale) !!}" /> {{ $field['title'] }}</label>

					<div class="uk-form-controls">
						{!! Form::text(
								$field['name'].'_'.$locale,
								(method_exists($item, 'translations') ?	$item->translations($locale)->get($field['name']) : ''),
								array(
									(isset($field['args']['disabled']) && $field['args']['disabled'] ? 'disabled' : ''),
									'id'=>$field['name'].'_'.$locale,
									'class'=>'uk-width-1-1'
								)
							)
						!!}
					</div>
				</div>
			@endforeach
		@endif
	@endif

	@if($field['type']=='textarea')
		@foreach(config('formable.locales') as $locale)
			<div class="uk-form-row">
				<label class="uk-form-label" for="{{ $field['name'].'_'.$locale }}"><img src="/{!! config('formable.flagicons.'.$locale) !!}" /> {{ $field['title'] }}</label>

				<div class="uk-form-controls">
					{!! Form::textarea(
							$field['name'].'_'.$locale,
							(method_exists($item, 'translations') ?	$item->translations($locale)->get($field['name']) : ''),
							array(
								(isset($field['args']['disabled']) && $field['args']['disabled'] ? 'disabled' : ''),
								'class'=> ((array_key_exists('args', $field) && $field['args']['ckeditor']) ? 'ckeditor' : '').' uk-width-1-1'
							)
						)
					!!}
				</div>
			</div>

			@if($field['args']['ckeditor'])
				@if(isset($item) && $item->img()->exists())
					<div id="myndaslider" class="uk-margin-top">
						<div data-uk-slideset="{small: 4, medium: 6, large: 10}">
						    <div class="uk-slidenav-position">
						        <ul class="uk-grid uk-slideset">
						        	@foreach($item->img()->all() as $img)
						            	<li class="uk-text-center">
						            		<img style="max-height: 50px; width: auto;" src="/imagecache/article/{{ $img['name'] }}" />
						            	</li>
						            @endforeach
						        </ul>
						    </div>
						    <ul class="uk-slideset-nav uk-dotnav uk-flex-center uk-margin-top">...</ul>
						</div>
					</div>
				@endif
			@endif

			@if($field['args']['ckeditor'])
				@if(array_key_exists('args', $field) && $field['args']['ckeditor'])
					<script>
					//CKEDITOR.replace( '{!! $field['name'] !!}_{{ $locale }}' );
					</script><br>
				@endif
			@endif
		@endforeach
	@endif
@endforeach







@if(isset($item->fillableExtras))
	<h3>Aukareitir</h3>

	@foreach(config('formable.locales') as $locale)
		@foreach($item->fillableExtras as $k => $v)
			<div class="uk-form-row">
				<label class="uk-form-label" for="extra_{{ $locale }}_{{ strtolower(trim($k)) }}"><img src="/{!! config('formable.flagicons.'.$locale) !!}" /> {!! $v !!}</label>

				<div class="uk-form-controls">
					{!! Form::text(
							'extra_'.$locale.'_'.strtolower(trim($k)),
							(method_exists($item, 'extras') ? $item->extras($locale)->get(strtolower(trim($k))) : ''),
							array(
								'class'=> 'uk-width-1-1'
							)
						)
					!!}
				</div>
			</div>
		@endforeach
	@endforeach
@endif