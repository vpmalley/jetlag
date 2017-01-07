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
        .less('login.less')
		.less('travelbookCreator.less')
		.less('travelbook.less')
		.less('articleCreator.less')
        .less('user.less')
		.less('directives/*.less', 'public/css/jetlag.directives.css')
		.scripts([ //XXX: order matters for now, we should find a way to get rid of it
			bower_components+'jquery/dist/jquery.js',
			bower_components+'angular/angular.js',
			bower_components+'underscore/underscore.js',
			bower_components+'backbone/backbone.js',
			bower_components+'moment/min/moment-with-locales.js',
			bower_components+'ng-backbone/ng-backbone.js',
			bower_components+'bootstrap/dist/js/bootstrap.js',
			bower_components+'ng-file-upload/ng-file-upload.js',
			bower_components+'leaflet/dist/leaflet.js',
			bower_components+'angular-leaflet-directive/dist/angular-leaflet-directive.js',
			bower_components+'angular-elastic/elastic.js',
            bower_components+'moment-picker/dist/angular-moment-picker.js',
            bower_components+'pluralize/pluralize.js'
		], 'public/js/thirds.js')
		.scripts([
			'base.js',
			'app.js',
            'modelsMaker.js'
		], 'public/js/jetlag.js')
		.scriptsIn('resources/assets/js/directives', 'public/js/jetlag.directives.js')
		.scriptsIn('resources/assets/js/components', 'public/js/jetlag.components.js')
		.copy('resources/assets/js', 'public/js')
		.copy('resources/assets/images', 'public/images')
		.copy('bower_components/font-awesome/css/font-awesome.css', 'public/css/font-awesome.css')
		.copy('bower_components/font-awesome/fonts', 'public/fonts')
		.copy('bower_components/bootstrap/fonts', 'public/fonts')
		.copy('bower_components/leaflet/dist/images', 'public/images')
		.copy('bower_components/leaflet/dist/leaflet.css', 'public/css')
        .copy('bower_components/moment-picker/dist/angular-moment-picker.css', 'public/css')
		.copy('resources/assets/templates', 'public/templates')
		.version('js/thirds.js');
});
