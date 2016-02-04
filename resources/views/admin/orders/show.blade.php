@extends('admin.layout')

@section('content')

	<strong>{{ $order->created_at }}</strong>

	<h2>{{ $order->nafn }}</h2>

	<h3>Pöntunarnúmer #{{ strtoupper($order->reference) }}</h3>

	@if($order->confirmed)
		<p class="uk-text-success uk-text-bold">Pöntun staðfest</p>
	@else
		<p class="uk-text-warning uk-text-bold">Pöntun óstaðfest</p>
	@endif

	<div class="uk-grid uk-grid-small" data-uk-grid-margin>
		<div class="uk-width-medium-1-3">
			<dl class="uk-description-list-line">
				<dt>Netfang</dt>
				<dd>{{ $order->netfang }}</dd>
			</dl>
		</div>

		<div class="uk-width-medium-1-3">
			<dl class="uk-description-list-line">
				<dt>Símanúmer</dt>
				<dd>{{ $order->simi }}</dd>
			</dl>
		</div>

		<div class="uk-width-medium-1-3">
			<dl class="uk-description-list-line">
				<dt>Heimilisfang</dt>
				<dd>{{ $order->heimilisfang }}</dd>
			</dl>
		</div>

		<div class="uk-width-medium-1-3">
			<dl class="uk-description-list-line">
				<dt>Pnr. og staður</dt>
				<dd>{{ $order->pnr }} {{ $order->stadur }}</dd>
			</dl>
		</div>

		<div class="uk-width-medium-1-3">
			<dl class="uk-description-list-line">
				<dt>Greiðslumáti</dt>
				<dd>{{ $order->getPaymentOption() }}</dd>
			</dl>
		</div>

		<div class="uk-width-medium-1-3">
			<dl class="uk-description-list-line">
				<dt>Afhendingarmáti</dt>
				<dd>{{ $order->getShippingOption() }}</dd>
			</dl>
		</div>
	</div>
		
	@if(trim($order->athugasemd))
		<h3>Athugasemd frá viðskiptavini</h3>
		<p>{{ $order->athugasemd ?: 'Engin athugasemd' }}</p>
	@endif

	<h3>Pöntun</h3>
	<table class="uk-table uk-table-striped">
		<tr>
			{{-- <th>ID</th> --}}
			<th>Titill</th>
			<th>Vörunúmer</th>
			<th>Magn</th>
			<th>Upphæð</th>
		</tr>
		@foreach($order->getItems() as $item)
			<tr>
				{{-- <td>{{ $item->id }}</td> --}}
				<td>
					<h4>{{ $item->title }}</h4>
					@if($item->options)
						<small class="uk-display-block">
							@foreach(json_decode($item->options) as $k => $v)
								{{ $k }}: {{ $v }}<br>
							@endforeach
						</small>
					@endif
				</td>
				<td>{{ $item->vnr }}</td>
				<td>{{ $item->qty }}</td>
				<td>{{ kalFormatPrice($item->subtotal) }}</td>
			</tr>
		@endforeach

		<tr>
			<td colspan="3"></td>
			<td class="uk-text-bold">
				Samtals {{ kalFormatPrice($order->total()) }}
			</td>
		</tr>
	</table>


@stop