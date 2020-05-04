<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/news','User\NewsController', ['names' => 'news']);

/*Route::prefix('ajax')->group(function(){ // a mini-controllers here experiments
	

	Route::post('news/{id}/edit', function(Request $request, $id){
		$article = App\Models\News::findOrFail($id);
		return view('news/ajax-edit', [
			'article' => $article,
			'states' => $article->getModel()->getStates()
		]);
	})->where('id', '[0-9]+');

	//Route::patch('news/{id}','User\NewsController@update');


	
	Route::patch('news/{id}', function(Request $request, $id){
		$article = App\Models\News::findOrFail($id);
		dd(Auth::user());
		dd($article->toArray());
		dd('OMG');
	})->where('id', '[0-9]+');
});*/

