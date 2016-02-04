@extends('frontend.layout')

@section('title', isset($page) ? $page->title : '')

@section('content')

	<?php

	$showmenu = ($page->hasSubs() || $page->hasParent()) ? true : false;

	?>

	<div class="page">

		@if($showmenu)
			<div class="uk-grid uk-grid-medium" data-uk-grid-margin>
				<div class="uk-width-medium-1-4">
					<nav class="submenu">
						{!! kalMenuFrom(\Request::segment(1)) !!}
					</nav>
				</div>

				<div class="uk-width-medium-3-4">
					<div class="page-content">
						{!! cmsContent($page) !!}
					</div>
				</div>
			</div>
		@else
			<div class="page-content">
				{!! cmsContent($page) !!}
			</div>
		@endif


		@if(\Request::is('hafa-samband'))
			<div>
				<br>
				<hr>
				<br>
				@include('frontend.forms.contact')
			</div>
		@endif
	</div>

@stop