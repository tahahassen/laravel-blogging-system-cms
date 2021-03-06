<?php

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

use App\Http\Controllers\Blog\PostController;

Route::get('/', 'WelcomeController@index')->name('welcom');
Route::get('blog/posts/{post}',[PostController::class,'show'])->name('blog.show');
Route::get('blog/categories/{category}',[PostController::class,'category'])->name('blog.category');
Route::get('blog/tags/{tag}',[PostController::class,'tag'])->name('blog.tag');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth']], function () {
    Route::resource('categories', 'CategoryController');
    Route::resource('posts', 'PostController');
    Route::resource('tags', 'TagController') ;
    Route::get('trashed-Post', 'PostController@trashed')->name('trashed');
    Route::put('restore-Post/{post}', 'PostController@restore')->name('restore-posts');
    

});

Route::group(['middleware' => ['auth','admin']], function () {

    Route::get('users' , 'UsersController@index')->name('users.index');
    Route::PUT('users\{user}' , 'UsersController@makeAdmin')->name('users.make-admin');
    Route::get('users\profile', 'UsersController@edit')->name('users.edit-profile');
    Route::put('user\profile','UsersController@update')->name('users.update-profile');
});
