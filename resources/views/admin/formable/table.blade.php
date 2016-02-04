@extends('admin.layout')

@section('content')

	{!! $breadcrumbs->render() !!}

	<hr>

	@if(isset($currentItem))
		<h1>{{ $currentItem->title }}</h1>
	@endif

	@if($model->modelName() == 'News')
		<a class="uk-button uk-button-primary" href="/admin/{{ strtolower(trim($modelName)) }}/create?type={{ \Request::get('show') ?: 'news' }}">Bæta við<i class="uk-icon-plus-circle uk-margin-left"></i></a>

		<nav class="uk-margin-top">
			<ul class="uk-subnav uk-subnav-pill">
			    <li class="{{ \Request::get('show') == 'news' || !\Request::get('show') ? 'uk-active' : '' }}"><a href="/admin/news?show=news">Fréttir</a></li>
			    <!--<li class="{{ \Request::get('show') == 'article' ? 'uk-active' : '' }}"><a href="/admin/news?show=article">Greinar</a></li>
		    	<li class="{{ \Request::get('show') == 'thought' ? 'uk-active' : '' }}"><a href="/admin/news?show=thought">Hugleiðingar</a></li>
		    	<li class="{{ \Request::get('show') == 'seminar' ? 'uk-active' : '' }}"><a href="/admin/news?show=seminar">Námskeið</a></li>-->
			</ul>
		</nav>
	@else
		@if(isset($currentItem))
			@if($currentItem->hasParent())
				<a class="uk-button uk-button-primary" href="/admin/{{ strtolower(trim($modelName)) }}/{{ $currentItem->parent->id }}/subs">
					<i class="uk-icon-arrow-left uk-margin-right"></i>
					Til baka
				</a>
			@else
				<a class="uk-button uk-button-primary" href="/admin/{{ strtolower(trim($modelName)) }}">
					<i class="uk-icon-arrow-left uk-margin-right"></i>
					Heim
				</a>
			@endif
		@endif
		<a class="uk-button uk-button-primary" href="/admin/{{ strtolower(trim($modelName)) }}/create{{ isset($currentItem) ? '?parent_id='.$currentItem->id : '' }}">Bæta við<i class="uk-icon-plus-circle uk-margin-left"></i></a>
	@endif
	
	@if(isset($items))
		
		<table class="uk-table uk-table-striped uk-position-relative">

			<tbody class="uk-sortable" data-uk-sortable="{handleClass:'uk-sortable-handle', animation:0}">

				<tr>

					<th style="width: 10px;"></th>
					<th style="width: 120px;"></th>
					<th style="width: 20px;"></th>

					@foreach($model->listables() as $k => $listable)
						
						<th><?=$k?></th>

					@endforeach

					@if(isset($model->fillableExtras))
						<th>Extras</th>
					@endif

					<th></th>

				</tr>
				
				@foreach($items as $item)

					<tr class="sortTr" data-itemId="{{ $item->id }}">
						<td class="uk-sortable-handle uk-text-left"><i class="uk-icon-sort"></i></td>

						<td style="width: 120px !important;">
							<a title="Breyta" href="/admin/{{ strtolower($item->modelName()) }}/{{ $item->id }}/edit" class="uk-button-link uk-margin-small-right"><i class="uk-icon-edit"></i></a>

							@if(!$item->disable_parent_listing && $item->hasSubs())
								<a title="Undirflokkar" href="/admin/{{ strtolower($item->modelName()) }}/subs/{{ $item->id }}" class="uk-button uk-button-primary uk-button-mini uk-margin-small-right">
									<i class="uk-icon-pagelines"></i> {{ $item->getSubCount() }}
								</a>
							@endif

							@if (File::exists('../resources/views/admin/'.strtolower($item->modelName()).'/_actions.blade.php'))
								@if($item->products->count() > 0)
			    					@include('admin.'.strtolower($item->modelName()).'._actions', ['item' => $item])
			    				@endif
			    			@endif
						</td>

						<td>
							<img src="/imagecache/tiny/{{ $item->img()->first() }}" />
						</td>


						@foreach($item->listables() as $k => $listable)

							<td class="">
								{{ $item->{$listable} }}
							</td>

						@endforeach

						@if(isset($model->fillableExtras))
							<td>
								@foreach($model->fillableExtras as $k => $v)
									@if($item->extras()->get($k))
										<small>{{ $k }}: {{ $item->extras()->get($k) }}</small> 
									@endif
								@endforeach
							</td>
						@endif

						<td class="uk-text-right" style="width: 100px;">
							@if(isset($item) && $item->slug != '' && $item->slug[0]!='_')
								{!! Form::open(['action' => [$item->modelName().'Controller@destroy', $item->id], 'method' => 'delete', 'class'=>'uk-padding-remove uk-margin-remove']) !!}
									<button class="uk-button-link delete-button uk-float-right uk-margin-left" style="cursor: pointer;"><i class="uk-icon-trash-o uk-text-danger"></i></button>
								{!! Form::close() !!}
							@endif

							<button href="/admin/{{ strtolower($item->modelName()) }}/{{ $item->id }}/edit"
									title='Birta/fela'
									class="uk-button-link toggle-button"
									style="cursor: pointer;">
									<i class="uk-icon-eye {{ $item->status ? 'uk-text-success' : 'uk-text-danger' }}"></i>
							</button>
						</td>

					</tr>

				@endforeach

			</tbody>

		</table>

	@endif


	<script>
	$(function() {
		var sortable = UIkit.sortable($('.uk-sortable'), { /* options */ });

		$('table.uk-table').on('stop.uk.sortable', function() {
			$ordering = {
				_token: '{!! csrf_token() !!}',
				order: [],
				model: '{{ $model->modelName() }}'
			};

			$.each($('tr.sortTr'), function(i, v) {
				$itemId = $(v).attr('data-itemId');
				$ordering.order.push($itemId);
			});

			var json = JSON.stringify($ordering);
			console.log($ordering);

			$.post('/admin/formable/_reorder', $ordering, function(data) {
			}).success(function(data) {
				console.log('success', data);
			}).error(function(data) {
				console.log('error', data);
			});
		});

		$('button.toggle-button').click(function(e) {
			$itemId = $(this).parents('tr').attr('data-itemId');

			var ctx = $(this);

			var $icon = ctx.find('i');

			var item = {
				_token: '{!! csrf_token() !!}',
				model: '{{ $model->modelName() }}',
				id: $itemId
			}
			
			$.post('/admin/formable/_toggle', item, function(data) {
			}).success(function(data) {
				console.log(data);

				if(data.status == "0")
				{
					$icon.removeClass('uk-text-success').addClass('uk-text-danger');
				}
				if(data.status == "1")
				{
					$icon.removeClass('uk-text-danger').addClass('uk-text-success');
				}
			}).error(function(data) {

			});
		});

		$('button.delete-button').click(function(e) {
			e.preventDefault();

			var $form = $(this).parents('form');

			UIkit.modal.confirm("Ertu viss um að þú viljir eyða?", function() {
				$form.submit();
			});
		});
	});
	</script>

@stop