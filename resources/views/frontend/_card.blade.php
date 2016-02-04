<?php

$sizeClasses = 'uk-width-large-1-4 uk-width-medium-1-3 uk-width-small-1-2';

if(isset($size)) {
	switch($size) {
		case(1):
			$sizeClasses = 'uk-width-large-1-4 uk-width-medium-1-2';
			break;
		case(2):
			$sizeClasses = 'uk-width-medium-1-2 uk-width-small-1-1';
			break;
		default:
			$sizeClasses = 'uk-width-large-1-4 uk-width-medium-1-2 uk-width-small-1-2';
			break;
	}
}

if( ! isset($path)) {
	$path = '/' . \Request::path() .'/'. (isset($slug) ? $slug : $item->slug);
}

?>
<div class="{{ $sizeClasses }}">
	<div href="{{ $path }}" class="card">
		<a href="{{ $path }}"
		   class="card__image"
		   style="background: #FFF url('/imagecache/product/{{ isset($image) ? $image : $item->img()->first() }}') center center no-repeat;
		          background-size: {{ isset($fillimage) && $fillimage ? 'cover' : 'contain' }};">
			{{-- <img src="/imagecache/product/{{ isset($image) ? $image : $item->img()->first() }}" /> --}}
		</a>

		<div class="card__text">
			<div><a href="{{ $path }}">{{ isset($title) ? $title : $item->title }}</a>{!! isset($item->price) ? '<span class="price"><br>'.$item->price_formatted.'</span>' : '' !!}</div>
		</div>

		{{-- @if(isset($item->price) && !$item->options()->exists())
			@include('frontend._addtocart', ['item' => $item])
		@endif --}}
	</div>
</div>