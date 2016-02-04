<body>
	<strong>Nafn:</strong> {{ ucwords(trim($nafn)) }}<br>
	<strong>Netfang:</strong> {{ trim(strtolower($netfang)) }}<br>
	<strong>Sími:</strong> {{ trim($simi) }}<br><br>

	<strong>Skilaboð</strong><br>
	{{ trim($skilabod) }}<br><br>

	
	@if(isset($extras) && !empty($extras))
		<table style="border: 1px solid #EEE; border-collapse: collapse; padding: 10px;">
			<tr>
				@foreach($column_keys as $k => $v)
					<th style="text-align: left; vertical-align: top; border: 1px solid #EEE; border-collapse: collapse; padding: 10px;">{{ $k }}</th>
				@endforeach
			</tr>
			@foreach($extras as $key => $extra)
				<tr>
					@foreach($extra as $k => $v)
						@if(in_array($k, $column_keys))
							<td style="text-align: left; vertical-align: top; border: 1px solid #EEE; border-collapse: collapse; padding: 10px;">{{ $v }}</td>
						@endif
					@endforeach
				</tr>
			@endforeach
		</table>
	@endif
</body>