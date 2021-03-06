<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*use Zenith\Setting\LuaParser;
LuaParser::zenithify(LuaParser::read('/home/ranieri/forgottenserver/config.lua'));*/

Route::get('/', array('as' => 'home', 'uses' => 'NewsController@index'));
Route::get('news/{slug}', array('as' => 'news.show', 'uses' => 'NewsController@show'));

Route::get('login', array('as' => 'session.create', 'before' => 'guest', 'uses' => 'SessionsController@create'));
Route::post('login', array('as' => 'session.store', 'before' => 'guest', 'uses' => 'SessionsController@store'));
Route::get('logout', array('as' => 'session.destroy', 'before' => 'auth', 'uses' => 'SessionsController@destroy'));

// TODO: Recovery key
//Route::get('lost-password', 'RemindersController@getR');
