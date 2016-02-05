@extends('frontend.layout')

@section('title', isset($page) ? $page->title : '')

@section('content')

	<div class="Page">
		{!! cmsContent($page) !!}
	</div>

@stop