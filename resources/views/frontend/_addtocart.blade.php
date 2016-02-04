<div class="card__addtocart" data-product-id="{{ $item->id }}">
	<div class="uk-flex uk-flex-middle uk-flex-space-between">
		<div class="uk-width-1-10">
			<div class="card__addtocart--inner">
				<a href="#" @click.prevent="decrQty"><i class="uk-icon-minus-circle"></i></a>
			</div>
		</div>
		<div class="uk-width-2-10">
			<input type="text" value="1" />
		</div>
		<div class="uk-width-1-10">
			<div class="card__addtocart--inner">
				<a href="#" @click.prevent="incrQty"><i class="uk-icon-plus-circle"></i></a>
			</div>
		</div>
		<div class="uk-width-6-10">
			<div class="card__addtocart--inner">
				<button @click="addToCart" class="special">Bæta í körfu<i class="uk-icon-shopping-cart uk-margin-left"></i></button>
			</div>
		</div>
	</div>
</div>