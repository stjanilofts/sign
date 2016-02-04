<div class="row">
	<div class="medium-12 columns">
		<h4 id="modalTitle"><em>"{{ $product->title }}"</em> var bætt í körfuna.</h4>

		<p><a class="takki hidden-for-large-up" href="/karfa/">Skoða körfu</a></p>
	</div>
</div>

<div class="row show-for-large-up">
	<div class="medium-12 columns kal-margin-top">
		@include('frontend.cart._cart', ['size' => 12])
	</div>
</div>