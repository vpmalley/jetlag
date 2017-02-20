<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
| We should register each route explicitly to list here all endpoints we offer.
|
*/

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

  // Authentication routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

  // Registration routes
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

  // Traveller routes
Route::get('traveller/{id}', 'Web\TravellerController@getIndex');
Route::get('traveller/{id}/display', 'Web\TravellerController@getDisplay');
Route::get('traveller/{id}/edit', 'Web\TravellerController@getEdit');
Route::post('traveller/{id}/edit', 'Web\TravellerController@postEdit');

Route::get('me', 'Web\TravellerController@getMe');
Route::get('me/display', 'Web\TravellerController@getMyDisplay');
Route::get('me/edit', 'Web\TravellerController@getMyEdit');

  // Test only - TODO: remove before to merge in master
Route::get('travelbook/create', 'Web\TravelbookController@getCreate');
Route::get('travelbook/edit', 'Web\TravelbookController@getEdit');
Route::get('article/create/{id}', 'Web\ArticleController@getCreate');
Route::get('article/edit', 'Web\ArticleController@getEdit');

  // Travelbook routes
Route::get('travelbook/{id}', 'Web\TravelbookController@getDisplay');
Route::get('travelbook/{id}/display', 'Web\TravelbookController@getDisplay');
Route::get('travelbook/{id}/edit', 'Web\TravelbookController@getEdit');
Route::post('travelbook/{id}/edit', 'Web\TravelbookController@postEdit');

  // Article routes
Route::get('article/{id}', 'Web\ArticleController@getDisplay');

Route::resource('api/0.1/articles', 'Rest\RestArticleController', ['except' => ['create']]);
Route::resource('api/0.1/pictures', 'Rest\RestPictureController', ['except' => ['create']]);

Route::get('api/0.1/search/articles', 'Rest\SearchArticleController@search');

Route::post('api/0.1/pix/upload', 'Rest\RestPictureController@upload');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
  'traveller/{id}' => 'Web\TravellerController',
  'article/{id}' => 'Web\ArticleController',
]);
