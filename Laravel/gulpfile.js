var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	var bower_components = '../../../bower_components/';
    mix.less('app.less')
		.scripts([
			bower_components+'angular/angular.js',
			bower_components+'backbone/backbone.js',
			bower_components+'jquery/dist/jquery.js',
			bower_components+'moment/moment.js',
			bower_components+'underscore/underscore.js'
		])
		.version('js/all.js');
});
