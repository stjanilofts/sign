@extends('admin.layout')

@section('content')

	@if(isset($items))
		
		<table class="uk-table uk-table-striped uk-position-relative">

			<tbody class="uk-sortable" data-uk-sortable="{handleClass:'uk-sortable-handle', animation:0}">

				<tr>
					<th style="width: 15%;">Dags</th>
					<th>Pöntunarnr.</th>
					<th style="width: 45%;">Nafn</th>
					<th>Staða</th>
					<th>Upphæð</th>
					<th>&nbsp;</th>
				</tr>

				@foreach($items as $item)

					<tr data-itemId="{{ $item->id }}">
						<td class="uk-text-left">
							{{ $item->created_at }}
						</td>

						<td>#{{ strtoupper($item->reference) }}</td>

						<td style="width: 100px;">
							<a href="/admin/orders/{{ $item->id }}">
								{{ $item->nafn }}
							</a>
						</td>

						<td>
							@if($item->confirmed)
								<span class="uk-text-success">Pöntun staðfest</span>
							@else
								<span class="uk-text-warning">Pöntun óstaðfest</span>
							@endif
						</td>

						<td>{{ kalFormatPrice($item->total()) }}</td>

						<td class="uk-text-right">
							{!! Form::open(['action' => ['OrdersController@destroy', $item->id], 'method' => 'delete', 'class'=>'uk-padding-remove uk-margin-remove']) !!}
								<button class="uk-button-link delete-button uk-float-right uk-margin-left" style="cursor: pointer;"><i class="uk-icon-trash-o uk-text-danger"></i></button>
							{!! Form::close() !!}
						</td>
					</tr>

				@endforeach

			</tbody>

		</table>

	@endif


	<script>
	$(function() {
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