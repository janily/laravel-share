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

// Route::get('/', function()
// {
// 	return View::make('hello');
// });

Route::get('/',array('as'=>'index_page','uses'=>'ImageController@getIndex'));

Route::post('/',array('as'=>'index_page_post','before'=>'csrf','uses' => 'ImageController@postIndex'));

Route::get('snatch/{id}', 
	array('as' => 'get_image_information',
	'uses' => 'ImageController@getSnatch'))
	->where('id','[0-9]+');

// 显示所有的图片
Route::get('all',array('as' => 'all_images','uses' => 'ImageController@getAll'));

// 删除图片

Route::get('delete/{id}',array('as' => 'delete_image','uses' => 'ImageController@getDelete'))
		->where('id','[0-9]+');