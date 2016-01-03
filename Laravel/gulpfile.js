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
		.less('home.less')
		.scripts([ //XXX: order matters for now, we should find a way to get rid of it
			bower_components+'jquery/dist/jquery.js',
			bower_components+'angular/angular.js',
			bower_components+'underscore/underscore.js',
			bower_components+'backbone/backbone.js',
			bower_components+'moment/moment.js',
			bower_components+'ng-backbone/ng-backbone.js',
			bower_components+'bootstrap/dist/js/bootstrap.js'
		], 'public/js/thirds.js')
		.scripts([
			'base.js',
			'app.js',
		], 'public/js/jetlag.js')
		.copy('resources/assets/js', 'public/js')
		.copy('resources/assets/images', 'public/images')
		.copy('bower_components/font-awesome/css/font-awesome.css', 'public/css/font-awesome.css')
		.copy('bower_components/font-awesome/fonts', 'public/fonts')
		.copy('bower_components/bootstrap/fonts', 'public/fonts')
		.version('js/thirds.js');
});
