@extends('frontend.layout')

@section('title', isset($page) ? $page->title : '')

@section('content')

	<div class="Page">
		{!! cmsContent($page) !!}

		@if(\Request::is('hafa-samband') || \Request::is('vertu-i-bandi') )
			<div>
				<hr>
				@include('frontend.forms.contact')
			</div>
		@endif
	</div>

@stop