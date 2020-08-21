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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/categories','CategoriesController@index')->name('categories');


// Route::get('/admin/quiz', 'Admin\QuizController@index')->name('quiz');
// Route::get('/admin/add-quiz', 'Admin\QuizController@AddView')->name('add-quiz');
// Route::post('/admin/add-quiz', 'Admin\QuizController@Addquiz')->name('submit-add-quiz');
// Route::get('/admin/edit-quiz/{id}', 'Admin\QuizController@Edit')->name('edit-quiz');
// Route::post('/admin/update-quiz', 'Admin\QuizController@Update')->name('update-quiz');
// Route::get('/admin/delete-quiz/{id}', 'Admin\QuizController@Delete')->name('dalete-quiz');
