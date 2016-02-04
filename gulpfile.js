var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix
    .sass('app.scss')
    .browserSync({
    	notify: true,
    	proxy: 'dunogfidur.dev'
	});
});