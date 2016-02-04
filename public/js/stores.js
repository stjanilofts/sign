var cartStore = {
	state: {
		items: [{foo:'bar'}]
	},
	getItems: function() {
		console.log(this.state.items);
	}
};